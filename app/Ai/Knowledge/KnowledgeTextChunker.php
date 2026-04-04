<?php

namespace App\Ai\Knowledge;

final class KnowledgeTextChunker
{
    public function __construct(
        private int $maxLength = 1200,
        private int $overlap = 200,
    ) {}

    /**
     * @return list<array{text: string, chunk_index: int}>
     */
    public function chunk(string $text): array
    {
        $text = trim($text);

        if ($text === '') {
            return [];
        }

        $max = max(1, $this->maxLength);
        $overlap = min(max(0, $this->overlap), $max - 1);
        $length = mb_strlen($text);
        $out = [];
        $start = 0;
        $index = 0;

        while ($start < $length) {
            $chunk = mb_substr($text, $start, $max);
            $out[] = ['text' => $chunk, 'chunk_index' => $index];
            $index++;

            if ($start + $max >= $length) {
                break;
            }

            $start += $max - $overlap;
        }

        return $out;
    }
}
