<?php
declare(strict_types=1);

/**
 * Placeholder service layer for future AI features (§50 of the project spec).
 * Not wired to any provider yet, and never required for the site to function.
 * When implemented, read the API key from env('AI_API_KEY') — never hardcode it.
 */
final class AIService
{
    public static function isConfigured(): bool
    {
        return env('AI_API_KEY') !== null;
    }

    public static function askGita(string $question): string
    {
        throw new RuntimeException('AIService is not yet implemented.');
    }

    public static function explainVerseSimply(int $verseId): string
    {
        throw new RuntimeException('AIService is not yet implemented.');
    }

    public static function findRelatedTeachings(int $verseId): array
    {
        throw new RuntimeException('AIService is not yet implemented.');
    }

    public static function summarizeTeaching(int $teachingId): string
    {
        throw new RuntimeException('AIService is not yet implemented.');
    }

    public static function generateReflectionQuestion(int $verseId): string
    {
        throw new RuntimeException('AIService is not yet implemented.');
    }
}
