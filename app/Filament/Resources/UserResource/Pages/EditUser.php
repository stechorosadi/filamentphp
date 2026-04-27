<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (filled($data['new_password'] ?? null)) {
            // Update password via raw query right here.
            // This bypasses the 'hashed' Eloquent cast entirely,
            // preventing any risk of double-hashing.
            DB::table('users')
                ->where('id', $this->getRecord()->getKey())
                ->update(['password' => Hash::make($data['new_password'])]);
        }

        // Strip all password fields so they are never passed to fill/update.
        // Also strips the 'password' key in case the create-section field
        // leaked into $data (dehydrateStateUsing + invisible section).
        unset(
            $data['password'],
            $data['current_password'],
            $data['new_password'],
            $data['new_password_confirmation'],
        );

        return $data;
    }
}
