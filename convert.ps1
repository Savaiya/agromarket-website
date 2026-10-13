# convert.ps1 - Converts all HTML files to PHP with includes + updated links
# Run from: "Main files\agriva\"

$dir = $PSScriptRoot
$htmlFiles = Get-ChildItem -Path $dir -Filter "*.html" -File

$titles = @{
    "index"           = "Home - Maize Farming"
    "index-2"         = "Home - Agriculture Drone"
    "index-3"         = "Home - Tomato Farming"
    "index-4"         = "Home - Vegetable Farming"
    "about"           = "About Us"
    "about-2"         = "About Us Two"
    "service"         = "Our Service"
    "service-2"       = "Our Service Two"
    "service-details" = "Service Details"
    "project"         = "Our Portfolio"
    "project-details" = "Portfolio Details"
    "team"            = "Our Team"
    "team-details"    = "Team Details"
    "shop-grid"       = "Shop"
    "shop-details"    = "Shop Details"
    "shop-cart"       = "Shop Cart"
    "checkout"        = "Checkout"
    "news-grid"       = "Blog Grid"
    "news"            = "Blog Standard"
    "news-details"    = "Blog Details"
    "pricing"         = "Pricing"
    "faq"             = "FAQ"
    "contact"         = "Contact Us"
    "404"             = "404 - Page Not Found"
}

foreach ($htmlFile in $htmlFiles) {
    $baseName  = $htmlFile.BaseName
    $phpFile   = Join-Path $dir "$baseName.php"
    $pageTitle = if ($titles.ContainsKey($baseName)) { $titles[$baseName] } else { $baseName }

    $lines = Get-Content $htmlFile.FullName -Encoding UTF8

    # Find start: first line AFTER <div id="smooth-content">
    $startLine = -1
    for ($i = 0; $i -lt $lines.Count; $i++) {
        if ($lines[$i] -match 'id="smooth-content"') {
            $startLine = $i + 1
            break
        }
    }

    # Find end: line just before <!-- Footer Section Start -->
    $endLine = -1
    for ($i = $lines.Count - 1; $i -ge 0; $i--) {
        if ($lines[$i] -match 'Footer Section Start') {
            $endLine = $i - 1
            break
        }
    }
    # Fallback: find <footer
    if ($endLine -eq -1) {
        for ($i = $lines.Count - 1; $i -ge 0; $i--) {
            if ($lines[$i] -match '<footer') {
                $endLine = $i - 1
                break
            }
        }
    }

    if ($startLine -eq -1 -or $endLine -eq -1) {
        Write-Warning "Could not find content boundaries in $($htmlFile.Name) - skipping."
        continue
    }

    # Extract body lines and replace .html refs with .php
    $bodyLines = $lines[$startLine..$endLine] | ForEach-Object {
        $line = $_ -replace '\.html"', '.php"'
        $line = $line -replace "\.html'", ".php'"
        $line = $line -replace '\.html\)', '.php)'
        $line
    }

    # Build output lines array
    $out = [System.Collections.Generic.List[string]]::new()
    $escapedTitle = $pageTitle -replace "'", "\'"
    $out.Add("<?php")
    $out.Add("`$pageTitle = '$escapedTitle';")
    $out.Add("require_once 'includes/header.php';")
    $out.Add("?>")
    $out.Add("")
    foreach ($l in $bodyLines) { $out.Add($l) }
    $out.Add("")
    $out.Add("<?php require_once 'includes/footer.php'; ?>")

    Set-Content -Path $phpFile -Value $out -Encoding UTF8
    Write-Host "Created: $baseName.php  ($($bodyLines.Count) body lines)"
}

Write-Host ""
Write-Host "Done! $($htmlFiles.Count) files converted."
