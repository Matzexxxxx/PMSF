<?php
include('config/config.php');
$zoom = !empty($_GET['zoom']) ? $_GET['zoom'] : null;
if (!empty($_GET['lat']) && !empty($_GET['lon'])) {
    $startingLat = $_GET['lat'];
    $startingLng = $_GET['lon'];
    $locationSet = 1;
} else {
    $locationSet = 0;
}
if ($blockIframe) {
    header('X-Frame-Options: DENY');
}
?>
<!DOCTYPE html>
<html lang="<?= $locale ?>">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="PokeMap">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#3b3b3b">
	<!--Font Awesome Icons-->
	<script src="https://use.fontawesome.com/1282794228.js"></script>
    <!-- Fav- & Apple-Touch-Icons -->
    <!-- Favicon -->
    <link rel="shortcut icon" href="static/appicons/favicon.ico"
          type="image/x-icon">
    <!-- non-retina iPhone pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/114x114.png"
          sizes="57x57">
    <!-- non-retina iPad pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/144x144.png"
          sizes="72x72">
    <!-- non-retina iPad iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/152x152.png"
          sizes="76x76">
    <!-- retina iPhone pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/114x114.png"
          sizes="114x114">
    <!-- retina iPhone iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/120x120.png"
          sizes="120x120">
    <!-- retina iPad pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/144x144.png"
          sizes="144x144">
    <!-- retina iPad iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/152x152.png"
          sizes="152x152">
    <!-- retina iPhone 6 iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/180x180.png"
          sizes="180x180">
    <?php
    if ($gAnalyticsId != "") {
        echo '<!-- Google Analytics -->
            <script>
                window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
                ga("create", "' . $gAnalyticsId . '", "auto");
                ga("send", "pageview");
            </script>
            <script async src="https://www.google-analytics.com/analytics.js"></script>
            <!-- End Google Analytics -->';
    }
    ?>
    <?php
    if ($piwikUrl != "" && $piwikSiteId != "") {
        echo '<!-- Piwik -->
            <script type="text/javascript">
              var _paq = _paq || [];
              _paq.push(["trackPageView"]);
              _paq.push(["enableLinkTracking"]);
              (function() {
                var u="//' . $piwikUrl . '/";
                _paq.push(["setTrackerUrl", u+"piwik.php"]);
                _paq.push(["setSiteId", "' . $piwikSiteId . '"]);
                var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
                g.type="text/javascript"; g.async=true; g.defer=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
              })();
            </script>
            <!-- End Piwik Code -->';
    }
    ?>
    <script>
        var token = '<?php echo (!empty($_SESSION['token'])) ? $_SESSION['token'] : ""; ?>';
    </script>
    <link rel="stylesheet" href="static/dist/css/app.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
    <script src="static/js/vendor/modernizr.custom.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body id="top">
<div class="wrapper">
    <!-- Header -->
    <header id="header">
        <a href="#nav"><span class="label"><?php echo i8ln('Options') ?></span></a>

        <h1><a href="#"><?= $title ?></a></h1>
        <?php
        if ($discordUrl != "") {
            echo '<a href="' . $discordUrl . '" target="_blank" style="margin-bottom: 5px; vertical-align: middle;padding:0 5px;">
            <img src="static/images/discord.png" border="0" style="float: right;">
        </a>';
        }
        if ($paypalUrl != "") {
            echo '<a href="' . $paypalUrl . '" target="_blank" style="margin-bottom: 5px; vertical-align: middle; padding:0 5px;">
            <img src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_74x21.png" border="0" name="submit"
                 title="PayPal - The safer, easier way to pay online!" alt="Donate" style="float: right;">
        </a>';
        }
        ?>
        <a href="#stats" id="statsToggle" class="statsNav" style="float: right;"><span class="label"><?php echo i8ln('Stats') ?></span></a>
    </header>
    <!-- NAV -->
    <nav id="nav">
        <div id="nav-accordion">
            <h3><b><i class="fa fa-map-marker fa-fw" style="font:Gerogia, serif;"></i>Anzeige</b></h3>
            <div>
                <?php
                if (!$noPokemon) {
                    echo '<div class="form-control switch-container">
                    <h3>Pokemon</h3>
                    <div class="onoffswitch">
                        <input id="pokemon-switch" type="checkbox" name="pokemon-switch" class="onoffswitch-checkbox"
                               checked>
                        <label class="onoffswitch-label" for="pokemon-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noRaids) {
                    echo '<div class="form-control switch-container" id="raids-wrapper">
                    <h3>Raids</h3>
                    <div class="onoffswitch">
                        <input id="raids-switch" type="checkbox" name="raids-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="raids-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <div id="raids-filter-wrapper" style="display:none">
                    <div class="form-control switch-container" id="active-raids-wrapper">
                        <h3>Raideier deaktivieren</h3>
                        <div class="onoffswitch">
                            <input id="active-raids-switch" type="checkbox" name="active-raids-switch"
                                   class="onoffswitch-checkbox" checked>
                            <label class="onoffswitch-label" for="active-raids-switch">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-control switch-container" id="min-level-raids-filter-wrapper">
                        <h3>Min. Raid Level</h3>
                        <select name="min-level-raids-filter-switch" id="min-level-raids-filter-switch">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="max-level-raids-filter-wrapper">
                        <h3>Max. Raid Level</h3>
                        <select name="max-level-raids-filter-switch" id="max-level-raids-filter-switch">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <?php
                if (!$noGymSidebar && (!$noGyms || !$noRaids)) {
                    echo '<div id="gym-sidebar-wrapper" class="form-control switch-container">
                    <h3>Gym Sidebar</h3>
                    <div class="onoffswitch">
                        <input id="gym-sidebar-switch" type="checkbox" name="gym-sidebar-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="gym-sidebar-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noGyms) {
                    echo '<div class="form-control switch-container">
                    <h3>Gyms</h3>
                    <div class="onoffswitch">
                        <input id="gyms-switch" type="checkbox" name="gyms-switch" class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="gyms-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <div id="gyms-filter-wrapper" style="display:none">
                    <div class="form-control switch-container" id="team-gyms-only-wrapper">
                        <h3>Team</h3>
                        <select name="team-gyms-filter-switch" id="team-gyms-only-switch">
                            <option value="0">Alle</option>
                            <option value="1">Weisheit (Mystic / Blau)</option>
                            <option value="2">Wagemut (Valor / Rot)</option>
                            <option value="3">Intuition (Instinct / Gelb)</option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="open-gyms-only-wrapper">
                        <h3>Freier Platz</h3>
                        <div class="onoffswitch">
                            <input id="open-gyms-only-switch" type="checkbox" name="open-gyms-only-switch"
                                   class="onoffswitch-checkbox" checked>
                            <label class="onoffswitch-label" for="open-gyms-only-switch">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-control switch-container" id="min-level-gyms-filter-wrapper">
                        <h3>Min. freie Plätze</h3>
                        <select name="min-level-gyms-filter-switch" id="min-level-gyms-filter-switch">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="max-level-gyms-filter-wrapper">
                        <h3>Max. freie Plätze</h3>
                        <select name="max-level-gyms-filter-switch" id="max-level-gyms-filter-switch">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="last-update-gyms-wrapper">
                        <h3>Nach Aktualisierung filtern</h3>
                        <select name="last-update-gyms-switch" id="last-update-gyms-switch">
                            <option value="0">Alle</option>
                            <option value="1">Letzte Stunde</option>
                            <option value="6">Letzte 6 Stunden</option>
                            <option value="12">Letzte 12 Stunden</option>
                            <option value="24">Letzte 24 Stunden</option>
                            <option value="168">Letzte Woche</option>
                        </select>
                    </div>
                </div>
                <?php
                if (!$noPokestops) {
                    echo '<div class="form-control switch-container">
                    <h3>Pokestops</h3>
                    <div class="onoffswitch">
                        <input id="pokestops-switch" type="checkbox" name="pokestops-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="pokestops-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if ($map != "monocle") {
                    echo '<div class="form-control switch-container" id = "lured-pokestops-only-wrapper" style = "display:none">
                    <select name = "lured-pokestops-only-switch" id = "lured-pokestops-only-switch">
                        <option value = "0"> Alle</option>
                        <option value = "1"> Nur Lockmodule </option>
                    </select>
                </div>';
                }
                ?>
                <?php
                if ($map != "monocle" && !$noScannedLocations) {
                    echo '<div class="form-control switch-container">
                    <h3> Scanned Locations </h3>
                    <div class="onoffswitch">
                        <input id = "scanned-switch" type = "checkbox" name = "scanned-switch" class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="scanned-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noSpawnPoints) {
                    echo '<div class="form-control switch-container">
                    <h3> Spawnpunkte </h3>
                    <div class="onoffswitch">
                        <input id="spawnpoints-switch" type="checkbox" name="spawnpoints-switch"
                               class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="spawnpoints-switch">
                            <span class="switch-label" data - on="On" data - off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noRanges) {
                    echo '<div class="form-control switch-container">
                    <h3>Reichweiten</h3>
                    <div class="onoffswitch">
                        <input id="ranges-switch" type="checkbox" name="ranges-switch" class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="ranges-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noHidePokemon) {
                    echo '<div class="form-control">
                    <label for="exclude-pokemon">
                        <h3>Ausgeblendete Pokemon:</h3>
                        <div style="max-height:165px;overflow-y:auto">
                            <select id="exclude-pokemon" multiple="multiple"></select>
                        </div>
                    </label>
					Blende möglichst viele überflüssige Pokemon aus, um die Performance der Map zu erhöhen
                </div>';
                }
                ?>
            </div>

            <?php
            if (!$noSearchLocation || !$noStartMe || !$noStartLast || !$noFollowMe) {
                echo '<h3><b><i class="fa fa-location-arrow fa-fw"></i>Location </b></h3>
            <div>';
            }
            ?>
            <?php
            if (!$noSearchLocation) {
                echo '<div class="form-control switch-container" style="display:{{is_fixed}}">
                <label for="next-location">
                    <h3>Change search location</h3>
                    <input id="next-location" type="text" name="next-location" placeholder="Change search location">
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noStartMe) {
                echo '<div class="form-control switch-container">
                    <h3> Starte Map an meiner Position </h3>
                    <div class="onoffswitch">
                        <input id = "start-at-user-location-switch" type = "checkbox" name = "start-at-user-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="start-at-user-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            }
            ?>
            <?php
            if (!$noStartLast) {
                echo '<div class="form-control switch-container">
                    <h3> Starte Map an letzter Location </h3>
                    <div class="onoffswitch">
                        <input id = "start-at-last-location-switch" type = "checkbox" name = "start-at-last-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="start-at-last-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            }
            ?>
            <?php
            if (!$noFollowMe) {
                echo '<div class="form-control switch-container">
                    <h3> Map folgt meiner Location </h3>
                    <div class="onoffswitch">
                        <input id = "follow-my-location-switch" type = "checkbox" name = "follow-my-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="follow-my-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            }
            ?>
            <?php
            if (!$noSpawnArea) {
                echo '<div id="spawn-area-wrapper" class="form-control switch-container">
                <h3> Spawn area </h3>
                <div class="onoffswitch">
                    <input id = "spawn-area-switch" type = "checkbox" name = "spawn-area-switch"
                           class="onoffswitch-checkbox"/>
                    <label class="onoffswitch-label" for="spawn-area-switch">
                        <span class="switch-label" data - on = "On" data - off = "Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>';
            }
            ?>
            <?php
            if (!$noSearchLocation || !$noStartMe || !$noStartLast || !$noFollowMe) {
                echo '</div>';
            }
            ?>

            <?php
            if (!$noNotifyPokemon || !$noNotifyRarity || !$noNotifyIv || !$noNotifySound || !$noNotifyRaid) {
                echo '<h3><b><i class="fa fa-star fa-fw"></i>Favoriten / Notification</b></h3>
            <div>';
            }
            ?>
            <?php
            if (!$noNotifyPokemon) {
                echo '<div class="form-control">
                <label for="notify-pokemon">
                    <h3>Favorisierte Pokemon</h3>
                    <div style="max-height:165px;overflow-y:auto">
                        <select id="notify-pokemon" multiple="multiple"></select>
                    </div>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyRarity) {
                echo '<div class="form-control">
                <label for="notify-rarity">
                    <h3>Nach Seltenheit</h3>
                    <div style="max-height:165px;overflow-y:auto">
                        <select id="notify-rarity" multiple="multiple"></select>
                    </div>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyIv) {
                echo '<div class="form-control">
                <label for="notify-perfection">
                    <h3>Min.IV </h3>
                    <input id="notify-perfection" type="text" name="notify-perfection"
                           placeholder="Minimum perfection %"/>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyLevel) {
                echo '<div class="form-control">
                <label for="notify-level">
                    <h3>'.i8ln('Min. Level').'</h3>
                    <input id="notify-level" type="text" name="notify-level"
                           placeholder="'.i8ln('Minimum level').'"/>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyRaid) {
                echo '<div class="form-control switch-container" id="notify-raid-wrapper">
                        <h3>Notify of Minimum Raid Level</h3>
                        <select name="notify-raid" id="notify-raid">
                            <option value="0">Disable</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>';
            }
            ?>
            <?php
            if (!$noNotifySound) {
                echo '<div class="form-control switch-container">
                <h3>Notify with sound</h3>
                <div class="onoffswitch">
                    <input id="sound-switch" type="checkbox" name="sound-switch" class="onoffswitch-checkbox"
                           checked>
                    <label class="onoffswitch-label" for="sound-switch">
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>';
            }
            ?>
            <?php
            if (!$noCriesSound) {
                echo '<div class="form-control switch-container" id="cries-switch-wrapper">
                <h3>Use Pok&eacute;mon cries</h3>
                <div class="onoffswitch">
                    <input id="cries-switch" type="checkbox" name="cries-switch" class="onoffswitch-checkbox"
                           checked>
                    <label class="onoffswitch-label" for="cries-switch">
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifySound) {
                echo '</div>';
            }
            ?>
            <?php
            if (!$noNotifyPokemon || !$noNotifyRarity || !$noNotifyIv || !$noNotifySound || !$noNotifyRaid) {
                echo '</div>';
            }
            ?>

            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '<h3><b><i class="fa fa-map-o fa-fw"></i>Map Style</b></h3>
            <div>';
            }
            ?>
            <?php
            if (!$noMapStyle) {
                echo '<div class="form-control switch-container">
                <h3>Map Style</h3>
                <select id="map-style"></select>
            </div>';
            }
            ?>
            <?php
            if (!$noIconSize) {
                echo '<div class="form-control switch-container">
                <h3>Icon Size</h3>
                <select name="pokemon-icon-size" id="pokemon-icon-size">
                    <option value="-16">Tiny</option>
                    <option value="-8">X-Small</option>
                    <option value="0">Small</option>
                    <option value="10">Normal</option>
                    <option value="20">Large</option>
                    <option value="20">X-Large</option>
                    <option value="30">XXL</option>
                    <option value="80">GIANT</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noGymStyle) {
                echo '<div class="form-control switch-container">
                <h3>Gym Marker Style</h3>
                <select name="gym-marker-style" id="gym-marker-style">
                    <option value="ingame">In-Game</option>
                    <option value="shield">Shield</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noLocationStyle) {
                echo '<div class="form-control switch-container">
                <h3>Location Icon Marker</h3>
                <select name="locationmarker-style" id="locationmarker-style"></select>
            </div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '</div>';
            }
			
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '<h3><b><i class="fa fa-comments fa-fw" aria-hidden="true"></i>Chat &amp; Infos</b></h3>
            <div>';
            }
            ?>
            <?php
            if (!$noMapStyle) {
                echo '<div class="form-control switch-container">
				<b>Das Team von RocketMap-Dortmund hat einen offenen Discord-Chatserver, auf dem jeder Trainer herzlich willkommen ist. Ihr könnt dort chatten, Erfahrungen austauschen und euch gegenseitig helfen. Auch Fragen zur Map können dort beantwortet werden.</b><br>
				<br>
				Über den Discord Server werden Map-News bekanntgegeben, ebenso gibt es dort einen Raidchannel sowie weitere Informationen zu Möglichkeiten des Supports unserer Arbeit.
				<br><br><b>
				Für die Teilnahme am Chat ist Discord erforderlich:<br>
				<a href="https://itunes.apple.com/de/app/discord-chat-for-gamers/id985746746?mt=8">► <u>Discord für iOS</u> ◄</a><br>
				<a href="https://play.google.com/store/apps/details?id=com.discord&hl=de">► <u>Discord für Android</u> ◄</a><br>
				<a href="https://discordapp.com/">► <u>Discord für PC</u> ◄</a><br>
				<br>
				Um dem Discord-Server von RocketMap-Dortmund beizutreten, <a href="https://discord.gg/zwsGCUS">klickt einfach hier</a>. Der Link führt direkt in unseren #rules-channel, bitte vor dem Schreiben gründlich lesen. Ihr könnt Discord problemlos parallel auf dem Handy und auf dem PC nutzen. Gerade für den Einstieg bietet sich der PC an.</b>
				<br>
            </div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '</div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '<h3><b><i class="fa fa-clock-o fa-fw"></i>Scanzeiten</b></h3>
            <div>';
            }
            ?>
            <?php
            if (!$noMapStyle) {
                echo '<div class="form-control switch-container">
				<center>Derzeit ist die Map durchgehend online (24/7 Betrieb)<br>
            </div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '</div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '<h3><b><i class="fa fa-sliders fa-fw"></i>Save Settings</b></h3>
            <div>';
            }
            ?>
            <?php
            if (!$noMapStyle) {
                echo '<div class="form-control switch-container">
				Du hast die Möglichkeit, deine Einstellungen der angezeigten Pokemon, Raids, Arenen etc zu speichern oder zuvor gespeicherte Einstellungen zu laden.<br><br>
				Beachte, dass dieses Feature nicht für jedes Gerät/Browser kompatibel ist. Auf einem Gerät mit iOS ist das exportieren/importieren der Einstellungen beispielsweise nicht (oder nur eingeschränkt) möglich.
				<br>
				<br>'
			?>


            <center>
                <button class="settings"
                        onclick="download('<?= addslashes($title) ?>', JSON.stringify(JSON.stringify(localStorage)))">
                    <i class="fa fa-upload" aria-hidden="true"></i> Exportieren
                </button>
                <input id="fileInput" type="file" style="display:none;" onchange="openFile(event)"/>
                <button class="settings"
                        onclick="document.getElementById('fileInput').click()">
                    <i class="fa fa-download" aria-hidden="true"></i> Importieren
                </button>
            </center>
			<?php
			echo '</div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '</div>';
            }
            ?>
        </div>
        <div>
            <center>
                <button class="settings"
                        onclick="confirm('Are you sure you want to reset settings to default values?') ? (localStorage.clear(), window.location.reload()) : false">
                    <i class="fa fa-refresh" aria-hidden="true"></i> Reset Settings
                </button>
            </center>
        </div>
    </nav>
    <nav id="stats">
        <div class="switch-container">
            <!--<div class="switch-container">
                <div><center><a href="stats">Full Stats</a></center></div>
            </div>-->
            <div class="switch-container">
                <center><h1 id="stats-ldg-label"><?php echo i8ln('Loading') ?>...</h1></center>
            </div>
            <div class="stats-label-container">
                <center><h1 id="stats-pkmn-label"></h1></center>
            </div>
            <div id="pokemonList" style="color: black;">
                <table id="pokemonList_table" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?php echo i8ln('Icon') ?></th>
                        <th><?php echo i8ln('Name') ?></th>
                        <th><?php echo i8ln('Count') ?></th>
                        <th>%</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pokeStatStatus" style="color: black;"></div>
            </div>
            <div class="stats-label-container">
                <center><h1 id="stats-gym-label"></h1></center>
            </div>
            <div id="arenaList" style="color: black;"></div>
            <div class="stats-label-container">
                <center><h1 id="stats-pkstop-label"></h1></center>
            </div>
            <div id="pokestopList" style="color: black;"></div>
        </div>
    </nav>
    <nav id="gym-details">
        <center><h1><?php echo i8ln('Loading') ?>...</h1></center>
    </nav>

    <div id="motd" title=""></div>

    <div id="map"></div>
</div>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.9.1/polyfill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/skel/3.0.1/skel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
<script src="https://code.createjs.com/soundjs-0.6.2.min.js"></script>
<script src="node_modules/push.js/bin/push.min.js"></script>
<script src="static/dist/js/app.min.js"></script>
<script src="static/js/vendor/classie.js"></script>
<script>
    var centerLat = <?= $startingLat; ?>;
    var centerLng = <?= $startingLng; ?>;
    var locationSet = <?= $locationSet; ?>;
    var zoom<?php echo $zoom ? " = " . $zoom : null; ?>;
    var minZoom = <?= $maxZoomOut; ?>;
    var maxLatLng = <?= $maxLatLng; ?>;

    var osmTileServer = '<?php echo $osmTileServer; ?>';
    var mapStyle = '<?php echo $mapStyle ?>';
    var hidePokemon = <?php echo $noHidePokemon ? '[]' : $hidePokemon ?>;
    var notifyPokemon = <?php echo $noNotifyPokemon ? '[]' : $notifyPokemon ?>;
    var notifyRarity = <?php echo $noNotifyRarity ? '[]' : $notifyRarity ?>;
    var notifyIv = <?php echo $noNotifyIv ? '""' : $notifyIv ?>;
    var notifyLevel = <?php echo $noNotifyLevel ? '""' : $notifyLevel ?>;
    var notifyRaid = <?php echo $noNotifyRaid ? 0 : $notifyRaid ?>;
    var enableRaids = <?php echo $noRaids ? 'false' : $enableRaids ?>;
    var activeRaids = <?php echo $activeRaids ?>;
    var minRaidLevel = <?php echo $minRaidLevel ?>;
    var maxRaidLevel = <?php echo $maxRaidLevel ?>;
    var enableGyms = <?php echo $noGyms ? 'false' : $enableGyms ?>;
    var gymSidebar = <?php echo $noGymSidebar ? 'false' : $gymSidebar ?>;
    var enablePokemon = <?php echo $noPokemon ? 'false' : $enablePokemon ?>;
    var enablePokestops = <?php echo $noPokestops ? 'false' : $enablePokestops ?>;
    var enableLured = <?php echo $map != "monocle" ? $enableLured : 0 ?>;
    var enableScannedLocations = <?php echo $map != "monocle" && !$noScannedLocations ? $enableScannedLocations : 'false' ?>;
    var enableSpawnpoints = <?php echo $noSpawnPoints ? 'false' : $enableSpawnPoints ?>;
    var enableRanges = <?php echo $noRanges ? 'false' : $enableRanges ?>;
    var notifySound = <?php echo $noNotifySound ? 'false' : $notifySound ?>;
    var criesSound = <?php echo $noCriesSound ? 'false' : $criesSound ?>;
    var enableStartMe = <?php echo $noStartMe ? 'false' : $enableStartMe ?>;
    var enableStartLast = <?php echo (!$noStartLast && $enableStartMe === 'false') ? $enableStartLast : 'false' ?>;
    var enableFollowMe = <?php echo $noFollowMe ? 'false' : $enableFollowMe ?>;
    var enableSpawnArea = <?php echo $noSpawnArea ? 'false' : $enableSpawnArea ?>;
    var iconSize = <?php echo $iconSize ?>;
    var locationStyle = '<?php echo $locationStyle ?>';
    var gymStyle = '<?php echo $gymStyle ?>';
    var spriteFile = '<?php echo $copyrightSafe ? 'static/icons-safe-1.png' : 'static/icons-im-1.png' ?>';
    var spriteFileLarge = '<?php echo $copyrightSafe ? 'static/icons-safe-1-bigger.png' : 'static/icons-im-1-bigger.png' ?>';
    var icons = '<?php echo $copyrightSafe ? 'static/icons-safe/' : 'static/icons-pokemon/' ?>';
    var triggerGyms = <?php echo $triggerGyms ?>;
    var noExGyms = <?php echo $noExGyms === true ? 'true' : 'false' ?>;
    var noParkInfo = <?php echo $noParkInfo === true ? 'true' : 'false' ?>;
    var onlyTriggerGyms = <?php echo $onlyTriggerGyms === true ? 'true' : 'false' ?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="static/dist/js/map.common.min.js"></script>
<script src="static/dist/js/map.min.js"></script>
<script src="static/dist/js/stats.min.js"></script>
<script defer
        src="https://maps.googleapis.com/maps/api/js?key=<?= $gmapsKey ?>&amp;callback=initMap&amp;libraries=places,geometry"></script>
<script defer src="static/js/vendor/richmarker-compiled.js"></script>
</body>
</html>
