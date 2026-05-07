<?php

namespace App\Filament\Actions;

use App\Services\DeepLService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;

class TranslateAction
{
    /**
     * Returns a SchemaActions component with a "Translate ID → EN" button.
     * A unique action name is derived from the field list to avoid Filament
     * deduplicating multiple instances in the same form.
     *
     * @param  string[]  $plainFields  Field base names for plain-text fields (TextInput/Textarea)
     * @param  string[]  $htmlFields  Field base names for HTML fields (RichEditor)
     */
    public static function make(array $plainFields = [], array $htmlFields = []): SchemaActions
    {
        $name = 'translateToEn_'.md5(implode('_', array_merge($plainFields, $htmlFields)));

        return SchemaActions::make([
            Action::make($name)
                ->label('Translate ID → EN')
                ->icon(Heroicon::OutlinedLanguage)
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Translate to English')
                ->modalDescription('This will overwrite all English fields with DeepL translations from the Indonesian content. Continue?')
                ->modalSubmitActionLabel('Yes, Translate')
                ->action(function (Get $get, Set $set) use ($plainFields, $htmlFields): void {
                    $service = app(DeepLService::class);

                    if ($plainFields) {
                        $values = array_map(fn (string $f): string => $get("{$f}.id") ?? '', $plainFields);
                        $translated = $service->translateBatch($values);
                        foreach ($plainFields as $i => $field) {
                            $set("{$field}.en", $translated[$i] ?? $values[$i]);
                        }
                    }

                    if ($htmlFields) {
                        $values = array_map(fn (string $f): string => $get("{$f}.id") ?? '', $htmlFields);
                        $translated = $service->translateBatch($values, isHtml: true);
                        foreach ($htmlFields as $i => $field) {
                            $set("{$field}.en", $translated[$i] ?? $values[$i]);
                        }
                    }

                    Notification::make()
                        ->title('Translated to English successfully.')
                        ->success()
                        ->send();
                }),
        ])->columnSpanFull();
    }
}
