<?php

namespace App\Services\Pdf;

class SimplePdfReport
{
    protected string $title;

    protected string $subtitle;

    protected array $meta = [];

    protected array $columns = [];

    protected array $rows = [];

    protected string $footerLeft = '';

    protected string $footerRight = '';

    protected float $pageWidth = 841.89;

    protected float $pageHeight = 595.28;

    protected float $marginLeft = 36.0;

    protected float $marginRight = 36.0;

    protected float $marginTop = 36.0;

    protected float $marginBottom = 36.0;

    protected array $objects = [];

    public function __construct(string $title = 'SIPFK - SISTEM INFORMASI PENGADUAN FASILITAS KAMPUS', string $subtitle = '')
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
    }

    public static function create(string $title = '', string $subtitle = ''): self
    {
        return new self($title, $subtitle);
    }

    public function setMeta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function addRow(array $row): self
    {
        $this->rows[] = $row;

        return $this;
    }

    public function setFooter(string $left, string $right): self
    {
        $this->footerLeft = $left;
        $this->footerRight = $right;

        return $this;
    }

    protected function escape(string $text): string
    {

        $text = iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $text) ?: $text;
        $text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);

        return $text;
    }

    public function render(): string
    {
        $printableWidth = $this->pageWidth - $this->marginLeft - $this->marginRight;

        $colWidths = [];
        $totalRatio = 0.0;
        foreach ($this->columns as $col) {
            $totalRatio += ($col['width'] ?? 1.0);
        }
        foreach ($this->columns as $idx => $col) {
            $colWidths[$idx] = ($col['width'] / $totalRatio) * $printableWidth;
        }

        $pageStreams = [];
        $currentPage = '';
        $currentY = $this->pageHeight - $this->marginTop;

        $startNewPage = function () use (&$currentPage, &$currentY, &$pageStreams) {
            if (! empty($currentPage)) {
                $pageStreams[] = $currentPage;
            }
            $currentPage = '';
            $currentY = $this->pageHeight - $this->marginTop;
        };

        $startNewPage();

        $drawHeader = function () use (&$currentPage, &$currentY, $printableWidth) {

            $currentPage .= "q\n";

            $currentPage .= "0.12 0.23 0.38 rg\n";
            $currentPage .= sprintf("%.2f %.2f %.2f %.2f re f\n", $this->marginLeft, $currentY - 32, $printableWidth, 32);

            $currentPage .= "1 1 1 rg\n";
            $currentPage .= "BT\n/F2 13 Tf\n";
            $currentPage .= sprintf("%.2f %.2f Td\n", $this->marginLeft + 12, $currentY - 21);
            $currentPage .= sprintf("(%s) Tj\nET\n", $this->escape($this->title));

            $currentY -= 32;

            $currentPage .= "0.96 0.97 0.98 rg\n";
            $currentPage .= sprintf("%.2f %.2f %.2f %.2f re f\n", $this->marginLeft, $currentY - 24, $printableWidth, 24);
            $currentPage .= "0.85 0.88 0.91 RG 1 w\n";
            $currentPage .= sprintf("%.2f %.2f %.2f %.2f re s\n", $this->marginLeft, $currentY - 24, $printableWidth, 24);

            $currentPage .= "0.2 0.2 0.2 rg\n";
            $currentPage .= "BT\n/F2 9 Tf\n";
            $currentPage .= sprintf("%.2f %.2f Td\n", $this->marginLeft + 12, $currentY - 16);
            $currentPage .= sprintf("(%s) Tj\nET\n", $this->escape($this->subtitle));

            if (! empty($this->meta)) {
                $metaStr = implode('  |  ', array_map(fn ($k, $v) => "$k: $v", array_keys($this->meta), array_values($this->meta)));
                $currentPage .= "0.4 0.4 0.4 rg\n";
                $currentPage .= "BT\n/F1 8 Tf\n";
                $currentPage .= sprintf("%.2f %.2f Td\n", $this->marginLeft + $printableWidth - (strlen($metaStr) * 4.4) - 12, $currentY - 16);
                $currentPage .= sprintf("(%s) Tj\nET\n", $this->escape($metaStr));
            }

            $currentY -= 32;
            $currentPage .= "Q\n";
        };

        $drawTableHeader = function () use (&$currentPage, &$currentY, $colWidths, $printableWidth) {
            $headerHeight = 22.0;
            $currentPage .= "q\n";
            $currentPage .= "0.20 0.35 0.55 rg\n";
            $currentPage .= sprintf("%.2f %.2f %.2f %.2f re f\n", $this->marginLeft, $currentY - $headerHeight, $printableWidth, $headerHeight);

            $x = $this->marginLeft;
            $currentPage .= "1 1 1 rg\n";
            foreach ($this->columns as $idx => $col) {
                $w = $colWidths[$idx];
                $align = $col['align'] ?? 'left';
                $text = $this->escape($col['label'] ?? '');
                $currentPage .= "BT\n/F2 8.5 Tf\n";

                $textX = $x + 5;
                if ($align === 'center') {
                    $textX = $x + ($w / 2) - (strlen($text) * 2.2);
                }
                $currentPage .= sprintf("%.2f %.2f Td\n", $textX, $currentY - 15);
                $currentPage .= sprintf("(%s) Tj\nET\n", $text);
                $x += $w;
            }
            $currentPage .= "Q\n";
            $currentY -= $headerHeight;
        };

        $drawHeader();
        $drawTableHeader();

        $rowNum = 0;
        foreach ($this->rows as $row) {
            $rowHeight = 24.0;
            $hasFollowUp = ! empty($row['follow_ups_text']);
            if ($hasFollowUp) {
                $rowHeight = 36.0;
            }

            if ($currentY - $rowHeight < $this->marginBottom + 20) {
                $startNewPage();
                $drawHeader();
                $drawTableHeader();
            }

            $rowNum++;
            $bgR = ($rowNum % 2 === 0) ? 0.98 : 1.0;
            $bgG = ($rowNum % 2 === 0) ? 0.98 : 1.0;
            $bgB = ($rowNum % 2 === 0) ? 0.99 : 1.0;

            $currentPage .= "q\n";

            $currentPage .= sprintf("%.2f %.2f %.2f rg\n", $bgR, $bgG, $bgB);
            $currentPage .= sprintf("%.2f %.2f %.2f %.2f re f\n", $this->marginLeft, $currentY - $rowHeight, $printableWidth, $rowHeight);

            $currentPage .= "0.88 0.88 0.88 RG 0.5 w\n";
            $currentPage .= sprintf("%.2f %.2f m %.2f %.2f l S\n", $this->marginLeft, $currentY - $rowHeight, $this->marginLeft + $printableWidth, $currentY - $rowHeight);

            $x = $this->marginLeft;
            foreach ($this->columns as $idx => $col) {
                $w = $colWidths[$idx];
                $key = $col['key'];
                $val = $row[$key] ?? '';
                $align = $col['align'] ?? 'left';

                if ($key === 'status') {

                    $st = strtolower((string) $val);
                    $badgeW = 50.0;
                    $badgeH = 13.0;
                    $badgeX = $x + ($w / 2) - ($badgeW / 2);
                    $badgeY = $currentY - 17;

                    if ($st === 'baru') {
                        $currentPage .= "1.0 0.95 0.8 rg\n";
                        $currentPage .= sprintf("%.2f %.2f %.2f %.2f re f\n", $badgeX, $badgeY, $badgeW, $badgeH);
                        $currentPage .= "0.6 0.4 0.0 rg\n";
                    } elseif ($st === 'diproses') {
                        $currentPage .= "0.85 0.92 1.0 rg\n";
                        $currentPage .= sprintf("%.2f %.2f %.2f %.2f re f\n", $badgeX, $badgeY, $badgeW, $badgeH);
                        $currentPage .= "0.0 0.3 0.7 rg\n";
                    } else {
                        $currentPage .= "0.85 0.96 0.88 rg\n";
                        $currentPage .= sprintf("%.2f %.2f %.2f %.2f re f\n", $badgeX, $badgeY, $badgeW, $badgeH);
                        $currentPage .= "0.1 0.5 0.2 rg\n";
                    }
                    $currentPage .= "BT\n/F2 7.5 Tf\n";
                    $currentPage .= sprintf("%.2f %.2f Td\n", $badgeX + ($badgeW / 2) - (strlen($st) * 2.2), $badgeY + 3.5);
                    $currentPage .= sprintf("(%s) Tj\nET\n", strtoupper($this->escape($st)));
                } else {
                    $currentPage .= "0.15 0.15 0.15 rg\n";
                    $currentPage .= "BT\n/F1 8 Tf\n";

                    $maxChars = max(5, (int) ($w / 4.4));
                    $text = (string) $val;
                    if (mb_strlen($text) > $maxChars) {
                        $text = mb_substr($text, 0, $maxChars - 2).'..';
                    }
                    $textEscaped = $this->escape($text);

                    $textX = $x + 4;
                    if ($align === 'center') {
                        $textX = $x + ($w / 2) - (strlen($textEscaped) * 2.0);
                    }
                    $textY = $hasFollowUp ? ($currentY - 13) : ($currentY - 15);
                    $currentPage .= sprintf("%.2f %.2f Td\n", $textX, $textY);
                    $currentPage .= sprintf("(%s) Tj\nET\n", $textEscaped);

                    if ($key === 'description' && $hasFollowUp) {
                        $fText = 'Tindak Lanjut: '.(string) $row['follow_ups_text'];
                        $maxF = max(5, (int) ($w / 3.8));
                        if (mb_strlen($fText) > $maxF) {
                            $fText = mb_substr($fText, 0, $maxF - 2).'..';
                        }
                        $currentPage .= "0.0 0.35 0.65 rg\n";
                        $currentPage .= "BT\n/F2 7 Tf\n";
                        $currentPage .= sprintf("%.2f %.2f Td\n", $x + 4, $currentY - 26);
                        $currentPage .= sprintf("(%s) Tj\nET\n", $this->escape($fText));
                    }
                }

                $x += $w;
            }

            $currentPage .= "Q\n";
            $currentY -= $rowHeight;
        }

        if (! empty($currentPage)) {
            $pageStreams[] = $currentPage;
        }

        $totalPages = count($pageStreams);

        $outputPages = [];
        foreach ($pageStreams as $pIdx => $stream) {
            $pageNum = $pIdx + 1;

            $footerStream = "q\n";
            $footerStream .= "0.75 0.75 0.75 RG 0.5 w\n";
            $footerStream .= sprintf("%.2f %.2f m %.2f %.2f l S\n", $this->marginLeft, $this->marginBottom + 14, $this->marginLeft + $printableWidth, $this->marginBottom + 14);

            $footerStream .= "0.45 0.45 0.45 rg\n";
            $footerStream .= "BT\n/F1 7.5 Tf\n";
            $footerStream .= sprintf("%.2f %.2f Td\n", $this->marginLeft, $this->marginBottom + 4);
            $footerStream .= sprintf("(%s) Tj\nET\n", $this->escape($this->footerLeft));

            $pageStr = sprintf('Halaman %d dari %d', $pageNum, $totalPages);
            $footerRightFull = trim($this->footerRight.'  |  '.$pageStr, ' | ');
            $footerStream .= "BT\n/F1 7.5 Tf\n";
            $footerStream .= sprintf("%.2f %.2f Td\n", $this->marginLeft + $printableWidth - (strlen($footerRightFull) * 3.8), $this->marginBottom + 4);
            $footerStream .= sprintf("(%s) Tj\nET\n", $this->escape($footerRightFull));
            $footerStream .= "Q\n";

            $outputPages[] = $stream.$footerStream;
        }

        return $this->compilePdf($outputPages);
    }

    protected function compilePdf(array $pageContents): string
    {
        $objects = [];
        $objIndex = 1;

        $catalogId = $objIndex++;

        $pagesId = $objIndex++;

        $font1Id = $objIndex++;

        $font2Id = $objIndex++;

        $pageObjIds = [];
        $pageContentPairs = [];

        foreach ($pageContents as $content) {
            $pageId = $objIndex++;
            $contentId = $objIndex++;
            $pageObjIds[] = $pageId;
            $pageContentPairs[] = ['pageId' => $pageId, 'contentId' => $contentId, 'content' => $content];
        }

        $objects[$catalogId] = sprintf('<< /Type /Catalog /Pages %d 0 R >>', $pagesId);

        $kidsStr = implode(' ', array_map(fn ($id) => "$id 0 R", $pageObjIds));
        $objects[$pagesId] = sprintf('<< /Type /Pages /Kids [%s] /Count %d >>', $kidsStr, count($pageObjIds));

        $objects[$font1Id] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[$font2Id] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        foreach ($pageContentPairs as $pair) {
            $pageId = $pair['pageId'];
            $contentId = $pair['contentId'];
            $content = $pair['content'];

            $streamData = gzcompress($content);
            $filter = '/Filter /FlateDecode ';
            $length = strlen($streamData);

            $objects[$pageId] = sprintf(
                '<< /Type /Page /Parent %d 0 R /MediaBox [0 0 %.2f %.2f] /Contents %d 0 R /Resources << /Font << /F1 %d 0 R /F2 %d 0 R >> >> >>',
                $pagesId,
                $this->pageWidth,
                $this->pageHeight,
                $contentId,
                $font1Id,
                $font2Id
            );

            $objects[$contentId] = sprintf(
                "<< %s/Length %d >>\nstream\n%s\nendstream",
                $filter,
                $length,
                $streamData
            );
        }

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];

        for ($i = 1; $i < $objIndex; $i++) {
            $offsets[$i] = strlen($pdf);
            $pdf .= sprintf("%d 0 obj\n%s\nendobj\n", $i, $objects[$i]);
        }

        $xrefPos = strlen($pdf);
        $pdf .= sprintf("xref\n0 %d\n", $objIndex);
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i < $objIndex; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= sprintf("trailer\n<< /Size %d /Root %d 0 R >>\nstartxref\n%d\n%%%%EOF", $objIndex, $catalogId, $xrefPos);

        return $pdf;
    }
}
