<?php
session_start();
include 'includes/dbconn.inc.php';

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy();
    header("Location: index.php");
    exit();
}

$klantID = isset($_SESSION["klantID"]) ? $_SESSION["klantID"] : null;
$voornaam = isset($_SESSION["voornaam"]) ? $_SESSION["voornaam"] : null;
$naam = isset($_SESSION["naam"]) ? $_SESSION["naam"] : null;
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;
$admin = isset($_SESSION["admin"]) ? $_SESSION["admin"] : null;

// Contact form handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_contact'])) {
    $name = htmlspecialchars($_POST['name']);
    $email_from = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $to = "your-roundcube-email@domain.com"; // Replace with your Roundcube email
    $subject = "Contact Form Submission from $name";
    $headers = "From: $email_from\r\nReply-To: $email_from\r\n";
    $body = "Name: $name\nEmail: $email_from\nMessage: $message";

    if (mail($to, $subject, $body, $headers)) {
        $success = "Bericht succesvol verzonden!";
    } else {
        $error = "Verzenden mislukt. Probeer opnieuw.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAQUA - Museum van de Toekomst</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    
 <style>
    :root {
        --primary-color: #D90429;
    }
    [data-theme="orange"] {
        --primary-color: #D89746;
    }
    .bg-primary { background-color: var(--primary-color); }
    .text-primary { color: var(--primary-color); }
    .hover\:bg-primary:hover { background-color: var(--primary-color); }
    .hover\:text-primary:hover { color: var(--primary-color); }
    .bg-primary-dark { background-color: #b50322; }
    [data-theme="orange"] .bg-primary-dark { background-color: #b37432; }
   
</style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--primary-color)',
                        secondary: '#118AB2',
                        accent: '#FFD700',
                        dark: '#1A1A2E',
                    }
                }
            }
        };
    </script>
</head>
<body class="min-h-screen bg-gradient-to-b from-dark to-[#0F0F1A]">
    <button id="scrollToTop" class="hidden fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-primary-dark transition">
        <i class="fas fa-chevron-up"></i>
    </button>

<nav id="navbar" class="fixed top-0 left-0 w-full bg-[rgba(26,26,46,0.8)] backdrop-blur-md z-50 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <a href="index.php" class="flex items-center">
                <span class="text-2xl font-bold text-white">MAQ<span class="text-primary">UA</span></span>
                <span class="w-2 h-2 rounded-full bg-primary ml-1"></span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="#nieuws" class="text-white hover:text-primary transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-primary after:transition-all hover:after:w-full">Nieuws</a>
                <a href="#over" class="text-white hover:text-primary transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-primary after:transition-all hover:after:w-full">Over</a>
                <a href="#locatie" class="text-white hover:text-primary transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:bg-primary after:transition-all hover:after:w-full">Locatie</a>
            </div>

            <div class="hidden lg:flex items-center space-x-6">
                <a href="#shop" class="flex items-center space-x-2 text-white hover:text-primary transition-colors">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Shop</span>
                </a>
                <?php if (isset($_SESSION["klantID"])): ?>
                    <a href="index.php?logout=true" class="flex items-center space-x-2 text-white hover:text-primary transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Uitloggen</span>
                    </a>
                <?php else: ?>
                    <a href="/aanmelden/" class="flex items-center space-x-2 text-white hover:text-primary transition-colors">
                        <i class="fas fa-user"></i>
                        <span>Account</span>
                    </a>
                <?php endif; ?>
                <a href="#tickets" class="bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition-colors flex items-center space-x-2">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Tickets</span>
                </a>
                <?php if ($admin == 1) { ?>
                    <a href="klanten.php" class="flex items-center space-x-3 text-white hover:text-primary">
                        <i class="fas fa-user-shield w-6"></i>
                        <span>Admin</span>
                    </a>
                <?php } ?>
                <button id="theme-toggle-desktop" class="text-white hover:text-primary transition-colors p-2" title="Toggle Theme">
                    <i class="fas fa-adjust"></i>
                </button>
            </div>

            <!-- Mobile Menu Button -->
<button id="mobile-menu-button" class="lg:hidden flex items-center text-white p-3">
    <i class="fas fa-bars text-xl"></i>
</button>
        </div>
    </div>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"></div>

<!-- Mobile Menu -->
<div id="mobile-menu" class="fixed top-0 right-0 w-full max-w-xs h-full bg-dark z-50 transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl overflow-y-auto lg:hidden">
    <div class="flex justify-end p-6">
        <button id="mobile-menu-close" class="text-white text-2xl hover:text-primary transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="flex flex-col p-6 space-y-6">
            <a href="#nieuws" class="text-white text-lg hover:text-primary py-4 px-4 border-b border-gray-700 block">Nieuws</a>
            <a href="#over" class="text-white text-lg hover:text-primary py-3 border-b border-gray-700">Over</a>
            <a href="#locatie" class="text-white text-lg hover:text-primary py-3 border-b border-gray-700">Locatie</a>
            
            <div class="pt-6 flex flex-col space-y-6">
                <a href="#shop" class="flex items-center space-x-3 text-white hover:text-primary">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span>Shop</span>
                </a>
                <?php if (isset($_SESSION["klantID"])): ?>
                    <a href="index.php?logout=true" class="flex items-center space-x-3 text-white hover:text-primary">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span>Uitloggen</span>
                    </a>
                <?php else: ?>
                    <a href="/aanmelden/" class="flex items-center space-x-3 text-white hover:text-primary">
                        <i class="fas fa-user w-6"></i>
                        <span>Account</span>
                    </a>
                <?php endif; ?>
                <a href="#tickets" class="bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary-dark flex items-center justify-center space-x-2">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Tickets</span>
                </a>
                <?php if ($admin == 1) { ?>
                    <a href="klanten.php" class="flex items-center space-x-3 text-white hover:text-primary">
                        <i class="fas fa-user-shield w-6"></i>
                        <span>Admin</span>
                    </a>
                <?php } ?>
                <button id="theme-toggle-mobile" class="flex items-center space-x-3 text-white hover:text-primary transition-colors">
                    <i class="fas fa-adjust w-6"></i>
                    <span>Toggle Theme</span>
                </button>
            </div>
        </div>
    </div>
</nav>

    <!-- Your original sections remain unchanged until #locatie -->
    <section class="min-h-screen relative flex items-center justify-center overflow-hidden pt-20">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('kmska.webp'); filter: brightness(0.3);"></div>
        <div class="container mx-auto px-4 pt-16 z-10 flex flex-col items-center">
            <div class="max-w-4xl mx-auto text-center animate-fade-in">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-4">Ontdek de geschiedenis van</h1>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-primary mb-12">Antwerpia</h2>
                <div class="flex flex-wrap justify-center gap-4 mb-16">
                    <a href="#over" class="btn-primary">
                        <span>Ontdek Meer</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#tickets" class="btn-secondary">
                        <span>Koop Tickets</span>
                        <i class="fas fa-ticket-alt"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="bg-[rgba(255,255,255,0.1)] p-6 rounded-lg backdrop-blur-sm transition-transform hover:transform hover:scale-105">
                        <span class="block text-4xl font-bold text-primary mb-2">50K+</span>
                        <span class="text-gray-300">Bezoekers per jaar</span>
                    </div>
                    <div class="bg-[rgba(255,255,255,0.1)] p-6 rounded-lg backdrop-blur-sm transition-transform hover:transform hover:scale-105">
                        <span class="block text-4xl font-bold text-primary mb-2">1000+</span>
                        <span class="text-gray-300">Artefacten</span>
                    </div>
                    <div class="bg-[rgba(255,255,255,0.1)] p-6 rounded-lg backdrop-blur-sm transition-transform hover:transform hover:scale-105">
                        <span class="block text-4xl font-bold text-primary mb-2">4.9</span>
                        <span class="text-gray-300">Gemiddelde beoordeling</span>
                    </div>
                </div>
            </div>
        </div>
<div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center hidden lg:flex">
    <div class="w-8 h-12 border-2 border-white rounded-full flex justify-center">
        <div class="w-1.5 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
    </div>
    <div class="mt-2">
        <i class="fas fa-chevron-down text-white"></i>
    </div>
</div>
    </section>

    <section id="nieuws" class="py-24 bg-gradient-to-b from-[#0F0F1A] to-dark">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Geschiedenis in <span class="text-primary">Beeld</span></h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="timeline-item bg-[rgba(255,255,255,0.05)] rounded-lg p-6 overflow-hidden">
                    <div class="timeline-dot"></div>
                    <div class="relative overflow-hidden rounded-lg mb-6 group">
                        <img src="placeholder.jpg" alt="3050" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark to-transparent opacity-60"></div>
                        <div class="absolute top-4 left-4 bg-primary text-white px-3 py-1 rounded-full font-bold">3050</div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Oprichting van de eerste nederzetting</h3>
                    <p class="text-gray-300 mb-4">De eerste kolonisten vestigden zich in wat nu bekend staat als Antwerpia, en legden de basis voor een bloeiende gemeenschap.</p>
                    <a href="#" class="inline-flex items-center text-primary hover:underline font-semibold">
                        <span>Lees meer</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="timeline-item bg-[rgba(255,255,255,0.05)] rounded-lg p-6 overflow-hidden">
                    <div class="timeline-dot"></div>
                    <div class="relative overflow-hidden rounded-lg mb-6 group">
                        <img src="placeholder.jpg" alt="3065" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark to-transparent opacity-60"></div>
                        <div class="absolute top-4 left-4 bg-primary text-white px-3 py-1 rounded-full font-bold">3065</div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Heropbouw van de grote handelshaven</h3>
                    <p class="text-gray-300 mb-4">Na de grote storm werd de haven herbouwd, groter en sterker dan ooit tevoren, wat leidde tot een handelsboom.</p>
                    <a href="#" class="inline-flex items-center text-primary hover:underline font-semibold">
                        <span>Lees meer</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="timeline-item bg-[rgba(255,255,255,0.05)] rounded-lg p-6 overflow-hidden">
                    <div class="timeline-dot"></div>
                    <div class="relative overflow-hidden rounded-lg mb-6 group">
                        <img src="placeholder.jpg" alt="3069" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark to-transparent opacity-60"></div>
                        <div class="absolute top-4 left-4 bg-primary text-white px-3 py-1 rounded-full font-bold">3069</div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Industriële revolutie en stadsuitbreiding</h3>
                    <p class="text-gray-300 mb-4">Nieuwe technologieën transformeerden de stad en leidden tot ongekende groei en welvaart voor de burgers.</p>
                    <a href="#" class="inline-flex items-center text-primary hover:underline font-semibold">
                        <span>Lees meer</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="timeline-item bg-[rgba(255,255,255,0.05)] rounded-lg p-6 overflow-hidden">
                    <div class="timeline-dot"></div>
                    <div class="relative overflow-hidden rounded-lg mb-6 group">
                        <img src="placeholder.jpg" alt="3075" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark to-transparent opacity-60"></div>
                        <div class="absolute top-4 left-4 bg-primary text-white px-3 py-1 rounded-full font-bold">3075</div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Wederopbouw na de oorlog</h3>
                    <p class="text-gray-300 mb-4">Na het conflict werd Antwerpia herbouwd met een visie op duurzaamheid en modernisering van infrastructuur.</p>
                    <a href="#" class="inline-flex items-center text-primary hover:underline font-semibold">
                        <span>Lees meer</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="timeline-item bg-[rgba(255,255,255,0.05)] rounded-lg p-6 overflow-hidden">
                    <div class="timeline-dot"></div>
                    <div class="relative overflow-hidden rounded-lg mb-6 group">
                        <img src="placeholder.jpg" alt="3080" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark to-transparent opacity-60"></div>
                        <div class="absolute top-4 left-4 bg-primary text-white px-3 py-1 rounded-full font-bold">3080</div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Opening van het MAQUA museum</h3>
                    <p class="text-gray-300 mb-4">Het museum opende zijn deuren om de rijke geschiedenis van Antwerpia te bewaren en te delen met toekomstige generaties.</p>
                    <a href="#" class="inline-flex items-center text-primary hover:underline font-semibold">
                        <span>Lees meer</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-gradient-to-br from-primary to-primary-dark rounded-lg p-8 flex flex-col justify-center text-white">
                    <h3 class="text-2xl font-bold mb-3">Verken onze volledige tijdlijn</h3>
                    <p class="mb-6">Ontdek meer dan 1000 jaar geschiedenis van Antwerpia met onze interactieve tijdlijn en tentoonstellingen.</p>
                    <a href="#" class="bg-white text-primary py-3 px-6 rounded-lg font-bold inline-flex items-center justify-center hover:bg-opacity-90 transition-all">
                        <span>Volledige Geschiedenis</span>
                        <i class="fas fa-long-arrow-alt-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="over" class="py-24 bg-gradient-to-b from-dark to-[#0F0F1A]">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl font-bold mb-8">Over <span class="text-primary">MAQUA</span></h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="flex flex-col items-center bg-[rgba(255,255,255,0.05)] p-4 rounded-lg">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <span class="text-center">Reis door de tijd</span>
                        </div>
                        <div class="flex flex-col items-center bg-[rgba(255,255,255,0.05)] p-4 rounded-lg">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-map-marked-alt text-white text-xl"></i>
                            </div>
                            <span class="text-center">Hart van Antwerpia</span>
                        </div>
                        <div class="flex flex-col items-center bg-[rgba(255,255,255,0.05)] p-4 rounded-lg">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-vr-cardboard text-white text-xl"></i>
                            </div>
                            <span class="text-center">Interactieve Ervaring</span>
                        </div>
                    </div>
                    <p class="text-lg mb-6">MAQUA is meer dan een museum - het is een reis door de tijd. Gevestigd in het hart van Antwerpia, vertellen we het verhaal van onze stad door de eeuwen heen. Van de eerste nederzettingen tot de bruisende metropool van vandaag.</p>
                    <p class="text-lg mb-8">Onze collectie omvat zeldzame artefacten, interactieve displays en meeslepende tentoonstellingen die de rijke geschiedenis van Antwerpia tot leven brengen. We nodigen bezoekers uit om deel uit te maken van dit verhaal en de geschiedenis te ervaren zoals nooit tevoren.</p>
                    <a href="#virtual-tour" class="btn-primary">
                        <span>Start Virtuele Tour</span>
                        <i class="fas fa-vr-cardboard ml-2"></i>
                    </a>
                </div>
                <div class="relative">
                    <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden shadow-2xl relative">
                        <video class="w-full h-full object-cover" controls poster="/api/placeholder/1280/720">
                            <source src="/api/placeholder/video.mp4" type="video/mp4">
                            Je browser ondersteunt deze video niet.
                        </video>
                    </div>
                    <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-gradient-to-br from-primary to-transparent opacity-50 blur-2xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-48 h-48 rounded-full bg-gradient-to-tr from-secondary to-transparent opacity-30 blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gradient-to-b from-[#0F0F1A] to-dark">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Veelgestelde <span class="text-primary">Vragen</span></h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="faq-item bg-[rgba(255,255,255,0.05)] rounded-lg mb-6">
                    <div class="faq-question p-6 flex justify-between items-center cursor-pointer">
                        <h3 class="text-xl font-medium">Mag je foto's nemen in het museum?</h3>
                        <i class="fas fa-plus text-primary transition-transform"></i>
                    </div>
                    <div class="faq-answer px-6 pb-6">
                        <p class="text-gray-300 mb-4">Ja, maar je moet het eerst melden aan ons personeel wanneer je foto's wilt nemen.</p>
                        <a href="#photo-policy" class="text-primary hover:underline inline-flex items-center">
                            <span>Bekijk ons foto beleid</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="faq-item bg-[rgba(255,255,255,0.05)] rounded-lg mb-6">
                    <div class="faq-question p-6 flex justify-between items-center cursor-pointer">
                        <h3 class="text-xl font-medium">Wat voor artefacten kan je vinden?</h3>
                        <i class="fas fa-plus text-primary transition-transform"></i>
                    </div>
                    <div class="faq-answer px-6 pb-6">
                        <p class="text-gray-300 mb-4">We hebben een uitgebreide collectie historische objecten die de rijke geschiedenis van Antwerpia illustreren, waaronder:</p>
                        <ul class="list-disc pl-5 mb-4 text-gray-300">
                            <li>Archeologische vondsten uit de eerste nederzetting</li>
                            <li>Maritieme artefacten uit de handelsperiode</li>
                            <li>Industriële objecten uit de revolutie</li>
                            <li>Kunst en cultuurobjecten van door de eeuwen heen</li>
                        </ul>
                        <a href="#collection" class="text-primary hover:underline inline-flex items-center">
                            <span>Bekijk onze collectie</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="faq-item bg-[rgba(255,255,255,0.05)] rounded-lg mb-6">
                    <div class="faq-question p-6 flex justify-between items-center cursor-pointer">
                        <h3 class="text-xl font-medium">Zijn er rondleidingen beschikbaar?</h3>
                        <i class="fas fa-plus text-primary transition-transform"></i>
                    </div>
                    <div class="faq-answer px-6 pb-6">
                        <p class="text-gray-300 mb-4">Ja, we bieden dagelijks rondleidingen aan in verschillende talen. Deze kunnen vooraf worden geboekt of op de dag zelf, afhankelijk van beschikbaarheid.</p>
                        <a href="#tours" class="text-primary hover:underline inline-flex items-center">
                            <span>Bekijk rondleidingsopties</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="faq-item bg-[rgba(255,255,255,0.05)] rounded-lg mb-6">
                    <div class="faq-question p-6 flex justify-between items-center cursor-pointer">
                        <h3 class="text-xl font-medium">Is het museum toegankelijk voor mindervaliden?</h3>
                        <i class="fas fa-plus text-primary transition-transform"></i>
                    </div>
                    <div class="faq-answer px-6 pb-6">
                        <p class="text-gray-300 mb-4">Absoluut! MAQUA is volledig toegankelijk voor alle bezoekers. We beschikken over liften, rolstoelhellingen en aangepaste faciliteiten om iedereen een prettige ervaring te bieden.</p>
                        <a href="#accessibility" class="text-primary hover:underline inline-flex items-center">
                            <span>Meer over toegankelijkheid</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="faq-item bg-[rgba(255,255,255,0.05)] rounded-lg mb-6">
                    <div class="faq-question p-6 flex justify-between items-center cursor-pointer">
                        <h3 class="text-xl font-medium">Hebben jullie speciale evenementen?</h3>
                        <i class="fas fa-plus text-primary transition-transform"></i>
                    </div>
                    <div class="faq-answer px-6 pb-6">
                        <p class="text-gray-300 mb-4">We organiseren regelmatig speciale evenementen, tijdelijke tentoonstellingen en educatieve workshops. Bekijk onze evenementenkalender voor de meest actuele informatie.</p>
                        <a href="#events" class="text-primary hover:underline inline-flex items-center">
                            <span>Bekijk evenementenkalender</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="text-center mt-10">
                    <a href="#all-faqs" class="btn-secondary">
                        <span>Meer Veelgestelde Vragen</span>
                        <i class="fas fa-question-circle ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="locatie" class="py-24 bg-gradient-to-b from-dark to-[#0F0F1A]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Vind <span class="text-primary">Ons</span></h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="relative w-full h-96 rounded-xl overflow-hidden shadow-2xl">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2499.556541564274!2d4.391773976885712!3d51.2088225324711!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c3f6ec46f528b5%3A0x7b977ae12c2e8955!2sKMSKA%20-%20Koninklijk%20Museum%20voor%20Schone%20Kunsten%20Antwerpen!5e0!3m2!1snl!2sbe!4v1740658256093!5m2!1snl!2sbe" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" alt="Locatie kaart"></iframe>
                        <div class="absolute top-4 right-4">
                            <a href="https://maps.google.com" target="_blank" class="bg-primary text-white p-3 rounded-full hover:bg-primary-dark transition-colors">
                                <i class="fas fa-directions"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold mb-6">In het hart van Antwerpia</h3>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary p-3 rounded-lg text-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold mb-2">Adres</h4>
                                <p class="text-gray-300">Museumplein 123<br>3000 Antwerpia</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary p-3 rounded-lg text-white">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold mb-2">Openingstijden</h4>
                                <p class="text-gray-300">Dinsdag - Zondag: 10:00 - 18:00<br>Maandag: Gesloten</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary p-3 rounded-lg text-white">
                                <i class="fas fa-subway"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold mb-2">Transport</h4>
                                <p class="text-gray-300">
                                    Metro: Halte Museumplein (Lijn 1, 3)<br>
                                    Tram: Halte Kunststraat (Lijn 5, 9)<br>
                                    Parkeren: Ondergrondse garage beschikbaar
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <a href="#contact" class="btn-primary">
                            <span>Neem Contact Op</span>
                            <i class="fas fa-envelope ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Contact Form Section -->
    <section id="contact" class="py-24 bg-gradient-to-b from-dark to-[#0F0F1A]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Neem <span class="text-primary">Contact</span> Op</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>
            <div class="max-w-lg mx-auto bg-[rgba(255,255,255,0.05)] p-8 rounded-xl">
                <?php if (isset($success)): ?>
                    <p class="text-green-500 mb-4"><?php echo $success; ?></p>
                <?php elseif (isset($error)): ?>
                    <p class="text-red-500 mb-4"><?php echo $error; ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-6">
                        <label class="block text-white mb-2" for="name">Naam</label>
                        <input type="text" id="name" name="name" required class="w-full py-3 px-4 rounded-lg bg-[rgba(255,255,255,0.1)] text-white focus:outline-none">
                    </div>
                    <div class="mb-6">
                        <label class="block text-white mb-2" for="email">E-mail</label>
                        <input type="email" id="email" name="email" required class="w-full py-3 px-4 rounded-lg bg-[rgba(255,255,255,0.1)] text-white focus:outline-none">
                    </div>
                    <div class="mb-6">
                        <label class="block text-white mb-2" for="message">Bericht</label>
                        <textarea id="message" name="message" required class="w-full py-3 px-4 rounded-lg bg-[rgba(255,255,255,0.1)] text-white focus:outline-none h-32"></textarea>
                    </div>
                    <button type="submit" name="submit_contact" class="bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary-dark transition-colors w-full">Verstuur</button>
                </form>
            </div>
        </div>
    </section>

    <section id="tickets" class="py-24 bg-gradient-to-b from-[#0F0F1A] to-dark">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Koop <span class="text-primary">Tickets</span></h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="bg-[rgba(255,255,255,0.05)] rounded-xl p-8 transition-transform hover:transform hover:scale-105 hover:shadow-xl">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold mb-2">Basis Ticket</h3>
                        <div class="text-primary text-4xl font-bold mb-2">€15</div>
                        <p class="text-gray-300">Toegang tot alle permanente tentoonstellingen</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-3"></i>
                            <span>Permanente collecties</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-3"></i>
                            <span>Audio guide app</span>
                        </li>
                        <li class="flex items-center text-gray-500">
                            <i class="fas fa-times mr-3"></i>
                            <span>Speciale tentoonstellingen</span>
                        </li>
                        <li class="flex items-center text-gray-500">
                            <i class="fas fa-times mr-3"></i>
                            <span>VR-ervaringen</span>
                        </li>
                    </ul>
                    <a href="#koop-basis" class="block text-center bg-[rgba(255,255,255,0.1)] text-white py-3 rounded-lg font-semibold hover:bg-primary transition-colors">
                        Selecteer
                    </a>
                </div>
                <div class="bg-gradient-to-br from-primary to-primary-dark rounded-xl p-8 transform scale-105 shadow-xl relative">
                    <div class="absolute top-0 right-8 transform -translate-y-1/2">
                        <div class="bg-accent text-dark px-4 py-1 rounded-full font-bold text-sm">
                            POPULAIR
                        </div>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold mb-2">Premium Ticket</h3>
                        <div class="text-white text-4xl font-bold mb-2">€25</div>
                        <p class="text-gray-100">Volledige toegang inclusief speciale tentoonstellingen</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span>Permanente collecties</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span>Audio guide app</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span>Speciale tentoonstellingen</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span>1 VR-ervaring naar keuze</span>
                        </li>
                    </ul>
                    <a href="#koop-premium" class="block text-center bg-white text-primary py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Selecteer
                    </a>
                </div>
                <div class="bg-[rgba(255,255,255,0.05)] rounded-xl p-8 transition-transform hover:transform hover:scale-105 hover:shadow-xl">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold mb-2">Familieticket</h3>
                        <div class="text-primary text-4xl font-bold mb-2">€50</div>
                        <p class="text-gray-300">Voor 2 volwassenen en 2 kinderen</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-3"></i>
                            <span>Permanente collecties</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-3"></i>
                            <span>Audio guide app</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-3"></i>
                            <span>Speciale tentoonstellingen</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-3"></i>
                            <span>Kinderspeurtocht</span>
                        </li>
                    </ul>
                    <a href="#koop-familie" class="block text-center bg-[rgba(255,255,255,0.1)] text-white py-3 rounded-lg font-semibold hover:bg-primary transition-colors">
                        Selecteer
                    </a>
                </div>
            </div>
            <div class="text-center mt-16">
                <p class="text-gray-300 mb-6">Of selecteer hieronder een groeps- of schoolticket (voor 10+ personen)</p>
                <a href="#group-tickets" class="btn-secondary">
                    <span>Groeps- & Schooltickets</span>
                    <i class="fas fa-users ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-[#0D0D1A] pt-20 pb-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-16">
                <div>
                    <a href="#" class="flex items-center mb-6">
                        <span class="text-2xl font-bold text-white">MAQ<span class="text-primary">UA</span></span>
                        <span class="w-2 h-2 rounded-full bg-primary ml-1"></span>
                    </a>
                    <p class="text-gray-400 mb-6">Het MAQUA Museum brengt de rijke geschiedenis van Antwerpia tot leven met meeslepende tentoonstellingen en interactieve ervaringen.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6 relative inline-block">
                        Snelle Links
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary"></span>
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="#nieuws" class="text-gray-400 hover:text-primary transition-colors">Nieuws</a></li>
                        <li><a href="#over" class="text-gray-400 hover:text-primary transition-colors">Over Ons</a></li>
                        <li><a href="#collection" class="text-gray-400 hover:text-primary transition-colors">Collectie</a></li>
                        <li><a href="#events" class="text-gray-400 hover:text-primary transition-colors">Evenementen</a></li>
                        <li><a href="#tickets" class="text-gray-400 hover:text-primary transition-colors">Tickets</a></li>
                        <li><a href="#vacatures" class="text-gray-400 hover:text-primary transition-colors">Werken bij MAQUA</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6 relative inline-block">
                        Contact
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary"></span>
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary"></i>
                            <span class="text-gray-400">Museumplein 123, 3000 Antwerpia</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3 text-primary"></i>
                            <span class="text-gray-400">+32 123 456 789</span>
                        </li>

                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3 text-primary"></i>
                            <span class="text-gray-400">info@maqua-museum.be</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock mt-1 mr-3 text-primary"></i>
                            <span class="text-gray-400">Di-Zo: 10:00 - 18:00<br>Ma: Gesloten</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6 relative inline-block">
                        Blijf op de hoogte
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary"></span>
                    </h3>
                    <p class="text-gray-400 mb-4">Meld je aan voor onze nieuwsbrief en blijf op de hoogte van nieuwe tentoonstellingen en evenementen.</p>
                    <form class="mb-4">
                        <div class="flex">
                            <input type="email" placeholder="Je e-mailadres" class="custom-input py-3 px-4 rounded-l-lg w-full focus:outline-none">
                            <button type="submit" class="bg-primary text-white py-3 px-4 rounded-r-lg hover:bg-primary-dark transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    <p class="text-gray-500 text-sm">Door je aan te melden ga je akkoord met onze privacyvoorwaarden.</p>
                </div>
            </div>
            <div class="pt-8 mt-8 border-t border-gray-800 text-center">
                <p class="text-gray-500 text-sm">© 2025 MAQUA Museum. Alle rechten voorbehouden.</p>
                <div class="flex justify-center mt-4 space-x-6">
                    <a href="#privacy" class="text-gray-500 hover:text-gray-400 transition-colors text-sm">Privacybeleid</a>
                    <a href="#terms" class="text-gray-500 hover:text-gray-400 transition-colors text-sm">Gebruiksvoorwaarden</a>
                    <a href="#cookies" class="text-gray-500 hover:text-gray-400 transition-colors text-sm">Cookiebeleid</a>
                </div>
            </div>
        </div>
    </footer>

<script>
// Mobile menu functionality
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenuClose = document.getElementById('mobile-menu-close');
const mobileMenu = document.getElementById('mobile-menu');
const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
const mobileLinks = document.querySelectorAll('#mobile-menu a');

function toggleMobileMenu() {
    mobileMenu.classList.toggle('translate-x-full');
    mobileMenuOverlay.classList.toggle('opacity-0');
    mobileMenuOverlay.classList.toggle('pointer-events-none');
    document.body.classList.toggle('overflow-hidden');
}

// Event listeners
mobileMenuButton.addEventListener('click', toggleMobileMenu);
mobileMenuClose.addEventListener('click', toggleMobileMenu);
mobileMenuOverlay.addEventListener('click', toggleMobileMenu);

// Close menu when clicking on links
mobileLinks.forEach(link => {
    link.addEventListener('click', toggleMobileMenu);
});

// Theme toggle functionality (unchanged)
const themeToggleDesktop = document.getElementById('theme-toggle-desktop');
const themeToggleMobile = document.getElementById('theme-toggle-mobile');
const body = document.body;
const savedTheme = localStorage.getItem('theme');

if (savedTheme) {
    body.setAttribute('data-theme', savedTheme);
}

function toggleTheme() {
    body.getAttribute('data-theme') === 'orange' ?
        (body.removeAttribute('data-theme'), localStorage.setItem('theme', 'default')) :
        (body.setAttribute('data-theme', 'orange'), localStorage.setItem('theme', 'orange'));
}

themeToggleDesktop.addEventListener('click', toggleTheme);
themeToggleMobile.addEventListener('click', toggleTheme);

// Close menu when clicking outside (optional - overlay already handles this)
document.addEventListener('click', (e) => {
    if (!mobileMenu.contains(e.target) && 
        !mobileMenuButton.contains(e.target) && 
        !mobileMenu.classList.contains('translate-x-full')) {
        mobileMenu.classList.add('translate-x-full');
        mobileMenuOverlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.classList.remove('overflow-hidden');
    }
});
</script>
</body>
</html>