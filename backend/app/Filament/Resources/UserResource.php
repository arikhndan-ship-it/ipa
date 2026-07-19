<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canEdit($record): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canDelete($record): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canDeleteAny(): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function getPluralLabel(): string
    {
        return __('messages.users');
    }

    public static function getLabel(): string
    {
        return __('messages.user');
    }

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $isAdmin = $user?->role === 'admin';

        return $form
            ->schema([
                Forms\Components\Tabs::make(__('messages.user'))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('messages.account'))
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Section::make(__('messages.account_information'))
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(User::class, 'email', ignoreRecord: true),
                                        Forms\Components\TextInput::make('password')
                                            ->password()
                                            ->revealable()
                                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                            ->dehydrated(fn (?string $state): bool => filled($state))
                                            ->required(fn (string $operation): bool => $operation === 'create')
                                            ->maxLength(255),
                                        Forms\Components\Select::make('role')
                                            ->options([
                                                'admin' => __('messages.admin_role'),
                                                'author' => __('messages.author_role'),
                                            ])
                                            ->required()
                                            ->default('author')
                                            ->native(false)
                                            ->disabled(!$isAdmin),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('messages.profile'))
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\Section::make(__('messages.profile_information'))
                                    ->schema([
                                        Forms\Components\Textarea::make('bio')
                                            ->maxLength(65535)
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('avatar')
                                            ->label(__('messages.avatar_url'))
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('facebook_url')
                                            ->label(__('messages.facebook_url'))
                                            ->url()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('twitter_url')
                                            ->label(__('messages.twitter_url'))
                                            ->url()
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $isAdmin = $user?->role === 'admin';

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->colors([
                        'danger' => 'admin',
                        'success' => 'author',
                    ])
                    ->icons([
                        'heroicon-o-shield-check' => 'admin',
                        'heroicon-o-user' => 'author',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label(__('messages.articles'))
                    ->sortable()
                    ->numeric()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => __('messages.admin_role'),
                        'author' => __('messages.author_role'),
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record): bool => $record->id !== auth()->id()),
                Tables\Actions\Action::make('terminate_sessions')
                    ->label(__('messages.terminate_sessions'))
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading(__('messages.terminate_sessions_heading'))
                    ->modalDescription(__('messages.terminate_sessions_desc'))
                    ->modalSubmitActionLabel(__('messages.yes_terminate'))
                    ->action(function (User $record) {
                        DB::table('sessions')
                            ->where('user_id', $record->id)
                            ->where('id', '!=', request()->session()->getId())
                            ->delete();
                        \Filament\Notifications\Notification::make()
                            ->title(__('messages.sessions_terminated'))
                            ->body(__('messages.sessions_terminated_body'))
                            ->success()
                            ->send();
                    })
                    ->visible(fn (User $record): bool => $record->id !== auth()->id()),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
