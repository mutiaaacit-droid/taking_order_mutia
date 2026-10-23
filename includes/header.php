<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// ============================================
// BASE URL - SESUAIKAN DENGAN FOLDER PROJECT
// ============================================
$base_url = '/taking_order_mutia/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? $judul : 'Ruang Teduh Cafe'; ?></title>

    <!-- Favicon = Logo Brand -->
    <link rel="icon" type="image/png" href="<?= $base_url; ?>assets/logo-brand.png">
    <link rel="apple-touch-icon" href="<?= $base_url; ?>assets/logo-brand.png">
<!-- Phosphor Icons - Regular weight -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'rose-dusty': '#B55B72',
            'rose-dark':  '#A94F67',
            'rose-soft':  '#C97A8E',
            'blush':      '#F7C8D0',
            'blush-light':'#FADDE2',
            'cream':      '#FAF0EE',
            'cream-alt':  '#FFF6F6',
            'berry':      '#6E2E3F',
            'berry-soft': '#8B5A66',
            'border-pink':'#F5C2CB',
          },
          fontFamily: {
            'sans':    ['Inter', 'system-ui', 'sans-serif'],
            'display': ['"Playfair Display"', 'Georgia', 'serif'],
          },
          borderRadius: {
            'sm': '8px',
            'md': '12px',
            'lg': '16px',
          }
        }
      }
    }
    </script>

    <!-- Custom CSS -->
    <link href="<?= $base_url; ?>assets/css/style.css?v=<?= @filemtime(__DIR__ . '/../assets/css/style.css') ?: time(); ?>" rel="stylesheet">
</head>
<body class="bg-cream text-berry">