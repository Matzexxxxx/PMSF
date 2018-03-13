<?php
if (!file_exists('config/config.php')) {
    http_response_code(500);
    die("<h1>Config file missing</h1><p>Please ensure you have created your config file (<code>config/config.php</code>).</p>");
}
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
        function pokemonFilterImages($noPokemonNumbers)
        {
            global $mons;
            if (empty($mons)) {
                $json = file_get_contents('static/dist/data/pokemon.min.json');
                $mons = json_decode($json, true);
            }
            echo '<div class="pokemon-list">';
            $i = 0;
            $z = 0;
            foreach ($mons as $k => $pokemon) {
                if ($k > 386) {
                    break;
                }
                echo "<span class='pokemon-icon-sprite' data-value='" . $k . "'><span class='$k inner-bg' style='background-position:-" . $i * 48.25 . "px -".$z."px'></span>";
                if (!$noPokemonNumbers) {
                    echo "<span class='pokemon-number'>" . $k . "</span>";
                }
                echo "</span>";
                if ($i == 27) {
                    $i = -1;
                    $z = $z + 48.25;
                }
                $i++;
            }
            echo '</div>';
        }
    ?>

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
        <a href="#nav"><b><span class="label" style="color:white"><?php echo i8ln('Menü') ?></span></b></a>

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
        <?php if (!$noWeatherOverlay) {
            ?>
        <div id="currentWeather"></div>
        <?php
        } ?>
        <a href="#stats" id="statsToggle" class="statsNav" style="float: right;"><span class="label"><?php echo i8ln('Stats') ?></span></a>
    </header>
    <!-- NAV -->
    <nav id="nav">
        <div id="nav-accordion">
            <?php
            if (!$noPokemon) {
                ?>
            <h3 style="font-weight: bold"><i class="fa fa-map-marker fa-fw"></i><?php echo i8ln('Pokemon') ?></h3>
            <div>
                <?php
                echo '<div class=" form-control switch-container" style="float:none;height:35px;margin-bottom:0px;">
                    <h3>' . i8ln('Pokemon') . '</h3>
                    <div class="onoffswitch">
                        <input id="pokemon-switch" type="checkbox" name="pokemon-switch" class="onoffswitch-checkbox"
                               checked>
                        <label class="onoffswitch-label" for="pokemon-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>'; ?>
                <div id="pokemon-filter-wrapper" style="display:none">
                    <div id="tabs">
                        <ul>
                            <?php
                            if (!$noHidePokemon) {
                                ?>
                            <li><a href="#tabs-1"><?php echo i8ln('Ausblenden') ?></a></li>
                            <?php
                            } ?>
                            <?php
                            if (!$noExcludeMinIV) {
                                ?>
                            <li><a href="#tabs-2"><?php echo i8ln('Immer anzeigen') ?></a></li>
                            <?php
                            } ?>
                        </ul>
                        <?php
                        if (!$noHidePokemon) {
                            ?>
                        <div id="tabs-1">
                            <div class="form-control hide-select-2">
                                <label for="exclude-pokemon">
                                    <div class="pokemon-container">
                                        <input id="exclude-pokemon" type="text" readonly="true">
                                        <?php
                                        pokemonFilterImages($noPokemonNumbers); ?>
                                    </div>
                                    <a href="#" class="select-all"><?php echo i8ln('Alle') ?><div></a><a href="#" class="hide-all"><?php echo i8ln('Keine') ?></a>
                                </label>
                            </div>
                        </div>
                            <?php
                        } ?>
                        <?php
                        if (!$noExcludeMinIV) {
                            ?>
                        <div id="tabs-2">
                            <div class="form-control hide-select-2">
                                <label for="exclude-min-iv">
                                    <div class="pokemon-container">
                                        <input id="exclude-min-iv" type="text" readonly="true">
                                        <?php
                                        pokemonFilterImages($noPokemonNumbers); ?>
                                    </div>
                                    <a href="#" class="select-all"><?php echo i8ln('Alle') ?><div></a><a href="#" class="hide-all"><?php echo i8ln('Keine') ?></a>
                                </label>
                            </div>
                        </div>
                            <?php
                        } ?>
                    </div>

                    <div class="form-row min-stats-row">
                        <?php
                        if (!$noMinIV) {
                            echo '<div class="form-control" >
                            <label for="min-iv">
                                <h3>'.i8ln('Min IV').'</h3>
                                <input id="min-iv" type="number" min="0" max="100" name="min-iv" placeholder="'.i8ln('Min IV').'"/>
                            </label>
                        </div>';
                        } ?>
                        <?php
                        if (!$noMinLevel) {
                            echo '<div class="form-control">
                            <label for="min-level">
                                <h3>'.i8ln('Min Lvl').'</h3>
                                <input id="min-level" type="number" min="0" max="100" name="min-level" placeholder="'.i8ln('Min Lvl').'"/>
                            </label>
                        </div>';
                        } ?>
                    </div>
					<div>
					<br>
					Blende mehr Pokemon aus, um die Performance der Ladezeit zu erhöhen.<br><br>
					Unter 'Immer anzeigen' kannst du Pokemon auswählen, die trotz IV/Lvl Filter dennoch angezeigt werden sollen.
					</div>
                </div>
            </div>
            <?php
            }
            ?>
            <?php
            if (!$noRaids || !$noGyms) {
                ?>
            <h3 style="font-weight: bold"><i class="fa fa-shield fa-fw"></i><?php echo i8ln('Arenen/Raids') ?></h3>
                <div>
                    <?php
                    if (!$noRaids) {
                        echo '<div class="form-control switch-container" id="raids-wrapper">
                    <h3>' . i8ln('Raids') . '</h3>
                    <div class="onoffswitch">
                        <input id="raids-switch" type="checkbox" name="raids-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="raids-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                    } ?>
                    <div id="raids-filter-wrapper" style="display:none">
                        <div class="form-control switch-container" id="active-raids-wrapper">
                            <h3><?php echo i8ln('Nur aktive Raids') ?></h3>
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
                            <h3><?php echo i8ln('Min. Raid Level') ?></h3>
                            <select name="min-level-raids-filter-switch" id="min-level-raids-filter-switch">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="form-control switch-container" id="max-level-raids-filter-wrapper">
                            <h3><?php echo i8ln('Max. Raid Level') ?></h3>
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
                    <h3>' . i8ln('Arena-Sidebar') . '</h3>
                    <div class="onoffswitch">
                        <input id="gym-sidebar-switch" type="checkbox" name="gym-sidebar-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="gym-sidebar-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
					</div>';
					} ?>
                    <?php
                    if (!$noGyms) {
                        echo '<div class="form-control switch-container">
                    <h3>' . i8ln('Arenen') . '</h3>
                    <div class="onoffswitch">
                        <input id="gyms-switch" type="checkbox" name="gyms-switch" class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="gyms-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                    } ?>
                    <div id="gyms-filter-wrapper" style="display:none">
                        <div class="form-control switch-container" id="team-gyms-only-wrapper">
                            <h3><?php echo i8ln('Team') ?></h3>
                            <select name="team-gyms-filter-switch" id="team-gyms-only-switch">
                                <option value="0"><?php echo i8ln('Alle') ?></option>
                                <option value="1"><?php echo i8ln('Mystic (Blau)') ?></option>
                                <option value="2"><?php echo i8ln('Valor (Rot)') ?></option>
                                <option value="3"><?php echo i8ln('Instinct (Gelb)') ?></option>
                            </select>
                        </div>
                        <div class="form-control switch-container" id="open-gyms-only-wrapper">
                            <h3><?php echo i8ln('Geöffnete Arenen filtern') ?></h3>
                            <div class="onoffswitch">
                                <input id="open-gyms-only-switch" type="checkbox" name="open-gyms-only-switch"
                                       class="onoffswitch-checkbox" checked>
                                <label class="onoffswitch-label" for="open-gyms-only-switch">
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-control switch-container" id="ex-gyms-only-wrapper">
                            <h3>
							<?php echo i8ln('Nur Ex Raid Arenen') ?>
							</h3>
                            <div class="onoffswitch">
                                <input id="ex-gyms-only-switch" type="checkbox" name="ex-gyms-only-switch"
                                       class="onoffswitch-checkbox" checked>
                                <label class="onoffswitch-label" for="ex-gyms-only-switch">
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-control switch-container" id="min-level-gyms-filter-wrapper">
                            <h3><?php echo i8ln('Min. freie Plätze') ?></h3>
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
                            <h3><?php echo i8ln('Max. freie Plätze') ?></h3>
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
                            <h3><?php echo i8ln('Nach Scandatum filtern') ?></h3>
                            <select name="last-update-gyms-switch" id="last-update-gyms-switch">
                                <option value="0"><?php echo i8ln('Alle') ?></option>
                                <option value="1"><?php echo i8ln('Letzte Stunde') ?></option>
                                <option value="6"><?php echo i8ln('Letzte 6 Stunden') ?></option>
                                <option value="12"><?php echo i8ln('Letzte 12 Stunden') ?></option>
                                <option value="24"><?php echo i8ln('Letzte 24 Stunden') ?></option>
                                <option value="168"><?php echo i8ln('Letzte 7 Tage') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
            if (!$noSearchLocation || !$noStartMe || !$noStartLast || !$noFollowMe || !$noPokestops || !$noScannedLocations || !$noSpawnPoints || !$noRanges) {
                echo '<h3 style="font-weight: bold"><i class="fa fa-location-arrow fa-fw"></i>'.i8ln('Location & Sonstiges').'</h3>

            <div>'; ?>
            <?php
            if (!$noPokestops) {
                echo '<div class="form-control switch-container">
                    <h3>'.i8ln('Pokestops').'</h3>
                    <div class="onoffswitch">
                        <input id="pokestops-switch" type="checkbox" name="pokestops-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="pokestops-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            } ?>
            <?php
            if ($map != "monocle") {
                echo '<div class="form-control switch-container" id = "lured-pokestops-only-wrapper" style = "display:none">
                    <select name = "lured-pokestops-only-switch" id = "lured-pokestops-only-switch">
                        <option value = "0"> '.i8ln('Alle').'</option>
                        <option value = "1"> '.i8ln('Nur Lockmodule').' </option>
                    </select>
                </div>';
            } ?>
            <?php
            if ($map != "monocle" && !$noScannedLocations) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Gescannte Bereiche').' </h3>
                    <div class="onoffswitch">
                        <input id = "scanned-switch" type = "checkbox" name = "scanned-switch" class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="scanned-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            } ?>
                <?php
                if (!$noWeatherOverlay) {
                    echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Weather Anzeige').' </h3>
                    <div class="onoffswitch">
                        <input id="weather-switch" type="checkbox" name="weather-switch"
                               class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="weather-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                } ?>
            <?php
            if (!$noSpawnPoints) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Spawnpunkte').' </h3>
                    <div class="onoffswitch">
                        <input id="spawnpoints-switch" type="checkbox" name="spawnpoints-switch"
                               class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="spawnpoints-switch">
                            <span class="switch-label" data - on="On" data - off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            } ?>
            <?php
            if (!$noRanges) {
                echo '<div class="form-control switch-container">
                    <h3>' . i8ln('Reichweiten') . '</h3>
                    <div class="onoffswitch">
                        <input id="ranges-switch" type="checkbox" name="ranges-switch" class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="ranges-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            } ?>
            <?php
            if (!$noSearchLocation) {
                echo '<div class="form-control switch-container" style="display:{{is_fixed}}">
                <label for="next-location">
                    <h3>'.i8ln('Ort suchen').'</h3>
                    <input id="next-location" type="text" name="next-location" placeholder="'.i8ln('Str. oder Stadt eingeben').'">
                </label>
            </div>';
            } ?>
            <?php
            if (!$noStartMe) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Map an meiner Position starten').' </h3>
                    <div class="onoffswitch">
                        <input id = "start-at-user-location-switch" type = "checkbox" name = "start-at-user-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="start-at-user-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            } ?>
            <?php
            if (!$noStartLast) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Map an letzter Position starten').' </h3>
                    <div class="onoffswitch">
                        <input id = "start-at-last-location-switch" type = "checkbox" name = "start-at-last-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="start-at-last-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            } ?>
            <?php
            if (!$noFollowMe) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Folge meiner Position').' </h3>
                    <div class="onoffswitch">
                        <input id = "follow-my-location-switch" type = "checkbox" name = "follow-my-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="follow-my-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            } ?>
            <?php
            if (!$noSpawnArea) {
                echo '<div id="spawn-area-wrapper" class="form-control switch-container">
                <h3> ' . i8ln('Spawnreichweite der Pos.') . ' </h3>
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
                echo '</div>';
            }
            ?>
            <?php
            if (!$noNotifyPokemon || !$noNotifyRarity || !$noNotifyIv || !$noNotifySound || !$noNotifyRaid) {
                echo '<h3 style="font-weight: bold"><i class="fa fa-star fa-fw"></i>'.i8ln('Favoriten').'</h3>
            <div>';
            }
            ?>
            <?php
            if (!$noNotifyPokemon) {
                echo '<div class="form-control hide-select-2">
                    <label for="notify-pokemon">
                        <h3>'.i8ln('Notify of Pokemon').'</h3><a href="#" class="select-all" style="background-color:#3b3b3b;border-radius:3px;padding: 5px 10px;border-color: white;color:white">Alle</a>&nbsp;&nbsp;<a href="#" class="hide-all" style="background:#3b3b3b;border-radius:3px;padding: 5px 10px;border-color: white;color:white">Keine</a><br><br>
                        <div style="max-height:165px;overflow-y:auto;">
                            <input id="notify-pokemon" type="text" readonly="true"/>';
                pokemonFilterImages($noPokemonNumbers);
                echo '</div>
                    </label>
                </div>';
            }
            ?>
            <?php
            if (!$noNotifyRarity) {
                echo '<div class="form-control">
                <label for="notify-rarity">
                    <h3>'.i8ln('Fav. nach Seltenheit').'</h3>
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
                    <h3>'.i8ln('Fav. nach IV').'</h3>
                    <input id="notify-perfection" type="text" name="notify-perfection"
                           placeholder="'.i8ln('Minimum perfection').' %" style="float: right;width: 75px;text-align:center"/>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyLevel) {
                echo '<div class="form-control">
                <label for="notify-level">
                    <h3 style="float:left;">' . i8ln('Fav. nach Lvl') . '</h3>
                    <input id="notify-level" min="1" max="35" type="number" name="notify-level"
                           placeholder="' . i8ln('Level') . '" style="float: right;width: 75px;text-align:center"/>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyRaid) {
                echo '<div class="form-control switch-container" id="notify-raid-wrapper">
                        <h3>'.i8ln('Fav - Min. Raid Lvl').'</h3>
                        <select name="notify-raid" id="notify-raid">
                            <option value="0">' . i8ln('Disable') . '</option>
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
                <h3>'.i8ln('Favoriten mit Ton').'</h3>
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
                <h3>'.i8ln('Pokemon Töne').'</h3>
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
            if (!$noMapStyle || !$noDirectionProvider || !$noIconSize || !$noIconNotifySizeModifier || !$noGymStyle || !$noLocationStyle) {
                echo '<h3 style="font-weight: bold"><i class="fa fa-map-o fa-fw"></i>'.i8ln('Style').'</h3>
            <div>';
            }
            ?>
            <?php
            if (!$noMapStyle) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Map Style').'</h3>
                <select id="map-style"></select>
            </div>';
            }
            ?>
            <?php
            if (!$noDirectionProvider) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Navigator').'</h3>
                <select name="direction-provider" id="direction-provider">
                    <option value="apple">'.i8ln('Apple').'</option>
                    <option value="google">'.i8ln('Google').'</option>
                    <option value="waze">'.i8ln('Waze').'</option>
                    <option value="bing">'.i8ln('Bing').'</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noIconSize) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Icon Größe').'</h3>
                <select name="pokemon-icon-size" id="pokemon-icon-size">
                    <option value="-8">'.i8ln('XS').'</option>
                    <option value="-8">'.i8ln('S').'</option>
                    <option value="0">'.i8ln('M').'</option>
                    <option value="10">'.i8ln('L').'</option>
                    <option value="20">'.i8ln('XL').'</option>
                    <option value="30">'.i8ln('XXL').'</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noIconNotifySizeModifier) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Favoriten Größe').'</h3>
                <select name="pokemon-icon-notify-size" id="pokemon-icon-notify-size">
                    <option value="0">'.i8ln('Normal').'</option>
                    <option value="15">'.i8ln('L').'</option>
                    <option value="30">'.i8ln('XL').'</option>
                    <option value="45">'.i8ln('XXL').'</option>
                    <option value="60">'.i8ln('3XL').'</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noGymStyle) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Arenen Style').'</h3>
                <select name="gym-marker-style" id="gym-marker-style">
                    <option value="ingame">'.i8ln('InGame').'</option>
                    <option value="shield">'.i8ln('Schilder').'</option>
                    <option value="classic">'.i8ln('Klassisch').'</option>
                    <option value="idol">'.i8ln('Götter').'</option>
                    <option value="beasts">'.i8ln('Biester').'</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noLocationStyle) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Position-Icon/Bild').'</h3>
                <select name="locationmarker-style" id="locationmarker-style"></select>
            </div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noDirectionProvider || !$noIconSize || !$noIconNotifySizeModifier || !$noGymStyle || !$noLocationStyle) {
                echo '</div>';
            }
            ?>
			
			<h3 style="font-weight: bold"><i class="fa fa-sliders fa-fw"></i><?php echo i8ln('Einstellungen') ?></h3>
            <div>
				<span style="color: #3b3b3b"><b style="font-size:17px">Zurücksetzen:</b><br>Alle Einstellungen des Menüs werden auf Standard zurückgesetzt.</span>
				<div>
					<center>
						<button class="settings"
								onclick="confirm('Möchtest du die Einstellungen auf Standard zurücksetzen?') ? (localStorage.clear(), window.location.reload()) : false">
							<i class="fa fa-refresh" aria-hidden="true"></i> <?php echo i8ln('Zurücksetzen') ?>
						</button>
					</center>
				</div>
				<br>
				<span style="color: #3b3b3b"><b style="font-size:17px">Exportieren:</b><br>Speichere deine Einstellungen des Menüs indem du sie als Datei downloadest.<br></span>
				<span style="color: #3b3b3b"><b style="font-size:17px">Importieren:</b><br>Lade eine Datei hoch, um zuvor gespeicherte Einstellungen wieder herzustellen.</span>
				<div>
					<center>
						<button class="settings"
								onclick="download('<?= addslashes($title) ?>', JSON.stringify(JSON.stringify(localStorage)))">
							<i class="fa fa-upload" aria-hidden="true"></i> <?php echo i8ln('Exportieren') ?>
						</button>
					</center>
				</div>
				<div>
					<center>
						<input id="fileInput" type="file" style="display:none;" onchange="openFile(event)"/>
						<button class="settings"
								onclick="document.getElementById('fileInput').click()">
							<i class="fa fa-download" aria-hidden="true"></i> <?php echo i8ln('Importieren') ?>
						</button>
					</center>
				</div>
			</div>
        </div>

    </nav>
    <nav id="stats">
        <div class="switch-container">
            <!--<div class="switch-container">
                <div><center><a href="stats">Full Stats</a></center></div>
            </div>-->
            <div class="switch-container">
                <center><h1 id="stats-ldg-label"><?php echo i8ln('Lädt') ?>...</h1></center>
            </div>
            <div class="stats-label-container">
                <center><h1 id="stats-pkmn-label"></h1></center>
            </div>
            <div id="pokemonList" style="color: black;">
                <table id="pokemonList_table" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?php echo i8ln('Bild') ?></th>
                        <th><?php echo i8ln('Name') ?></th>
                        <th><?php echo i8ln('Anzahl') ?></th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
<script src="https://code.createjs.com/soundjs-0.6.2.min.js"></script>
<script src="node_modules/push.js/bin/push.min.js"></script>
<script src="node_modules/long/src/long.js"></script>
<script src="static/js/vendor/s2geometry.js"></script>
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
    var excludeMinIV = <?php echo $noExcludeMinIV ? '[]' : $excludeMinIV ?>;
    var minIV = <?php echo $noMinIV ? '""' : $minIV ?>;
    var minLevel = <?php echo $noMinLevel ? '""' : $minLevel ?>;
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
    var enableWeatherOverlay = <?php echo !$noWeatherOverlay ? $enableWeatherOverlay : 'false' ?>;
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
    var iconNotifySizeModifier = <?php echo $iconNotifySizeModifier ?>;
    var locationStyle = '<?php echo $locationStyle ?>';
    var gymStyle = '<?php echo $gymStyle ?>';
    var spriteFileLarge = '<?php echo $copyrightSafe ? 'static/icons-safe-1-bigger.png' : 'static/icons-im-1-bigger.png' ?>';
    var weatherSpritesSrc = '<?php echo $copyrightSafe ? 'static/sprites-safe/' : 'static/sprites-pokemon/' ?>';
    var icons = '<?php echo $copyrightSafe ? 'static/icons-safe/' : 'static/icons-pokemon/' ?>';
    var weatherColors = <?php echo json_encode($weatherColors); ?>;
    var mapType = '<?php echo $map; ?>';
    var triggerGyms = <?php echo $triggerGyms ?>;
    var noExGyms = <?php echo $noExGyms === true ? 'true' : 'false' ?>;
    var noParkInfo = <?php echo $noParkInfo === true ? 'true' : 'false' ?>;
    var onlyTriggerGyms = <?php echo $onlyTriggerGyms === true ? 'true' : 'false' ?>;
    var hidePokemonCoords = <?php echo $hidePokemonCoords === true ? 'true' : 'false' ?>;
    var directionProvider = '<?php echo $noDirectionProvider === true ? $directionProvider : 'google' ?>';
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="static/dist/js/map.common.min.js"></script>
<script src="static/dist/js/map.min.js"></script>
<script src="static/dist/js/stats.min.js"></script>
<script defer
        src="https://maps.googleapis.com/maps/api/js?v=3.31&amp;key=<?= $gmapsKey ?>&amp;callback=initMap&amp;libraries=places,geometry"></script>
<script defer src="static/js/vendor/richmarker-compiled.js"></script>
</body>
</html>
