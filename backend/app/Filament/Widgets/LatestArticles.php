<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestArticles extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return __('messages.articles');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Article::query()
                    ->with(['author', 'categories', 'translations'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('messages.title'))
                    ->limit(40)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->orWhereHas('translations', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('messages.author')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'secondary' => 'archived',
                    ]),
                Tables\Columns\TextColumn::make('view_count')
                    ->label(__('messages.views'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.created'))
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('messages.edit'))
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Article $record): string => route('filament.admin.resources.articles.edit', $record)),
            ])
            ->paginated(false)
            ->striped();
    }
}
