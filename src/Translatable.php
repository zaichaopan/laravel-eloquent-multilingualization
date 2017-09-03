<?php

namespace Zai\Translate;

trait Translatable
{
    public function translations()
    {
        return $this->morphMany(DatabaseTranslation::class, 'translatable');
    }

    public function currentTranslation()
    {
        return $this->morphMany(DatabaseTranslation::class, 'translatable')->where('locale', app()->getLocale());
    }

    public function hasTranslations()
    {
        return $this->translations()->exists();
    }

    public function hasTranslation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations()->whereLocale($locale)->exists();
    }

    public function translationOf($locale)
    {
        return $this->translations()
            ->whereLocale($locale)
            ->first();
    }

    public function getTranslationAttribute()
    {
        $translation = $this->translations->where('locale', app()->getLocale())->first();

        return $translation
            ? (object)$translation->data
            : $this->getDefaultTranslation();
    }

    public function addTranslation(array $attributes)
    {
        if ($translation = $this->translationOf($attributes['locale'])) {
            $translation->update($this->getTranslationData($attributes));
            return $translation->fresh();
        }

        return $this->translations()->create($this->getTranslationData($attributes));
    }

    public function updateTranslation(array $attributes)
    {
        if ($translation = $this->translationOf($attributes['locale'])) {
            return $translation->update($this->getTranslationData($attributes));
        }

        return $this->translations()->create($this->getTranslationData($attributes));
    }

    public function deleteTranslation($locale)
    {
        return $this->translationOf($locale)->delete();
    }

    public function deleteTranslations()
    {
        return $this->translations()->delete();
    }

    protected function getTranslationData(array $attributes)
    {
        $locale = $attributes['locale'];

        $data = [];

        foreach ($this->translatables as $translatable) {
            $value = array_key_exists($translatable, $attributes)
                ? $attributes[$translatable]
                : '';

            $data = array_merge($data, [$translatable => $value]);
        }

        return compact('locale', 'data');
    }

    protected function getDefaultTranslation()
    {
        $data = [];

        foreach ($this->translatables as $translatable) {
            $data = array_merge($data, [$translatable => $this->$translatable]);
        }

        return (object)$data;
    }
}
