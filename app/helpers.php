<?php

use League\CommonMark\CommonMarkConverter;

if (!function_exists('markdown')) {
    /**
     * Convert Markdown to HTML, or return HTML as-is if already HTML
     * 
     * @param string|null $content
     * @return string
     */
    function markdown(?string $content): string
    {
        if (empty($content)) {
            return '';
        }
        
        // Check if content is already HTML (contains HTML tags)
        if (preg_match('/<(p|div|h[1-6]|ul|ol|li|br)\b/i', $content)) {
            // Content is already HTML, return as-is
            return $content;
        }
        
        // Otherwise, treat as Markdown and convert
        $converter = new CommonMarkConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
        
        return $converter->convert($content)->getContent();
    }
}
