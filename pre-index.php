<?php
if ( ! file_exists( 'config/config.php' ) ) {
    http_response_code( 500 );
    die( "<h1>Config file missing</h1><p>Please ensure you have created your config file (<code>config/config.php</code>).</p>" );
}
include( 'config/config.php' );
$zoom        = ! empty( $_GET['zoom'] ) ? $_GET['zoom'] : null;
$encounterId = ! empty( $_GET['encId'] ) ? $_GET['encId'] : null;
if ( ! empty( $_GET['lat'] ) && ! empty( $_GET['lon'] ) ) {
    $startingLat = $_GET['lat'];
    $startingLng = $_GET['lon'];
    $locationSet = 1;
} else {
    $locationSet = 0;
}
if ( $blockIframe ) {
    header( 'X-Frame-Options: DENY' );
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
    <!-- Fav- & Apple-Touch-Icons -->
    <!-- Favicon -->
    <?php
    if ( $faviconPath != "" ) {
       echo '<link rel="shortcut icon" href="' . $faviconPath . '"
             type="image/x-icon">';
    } else {
       echo '<link rel="shortcut icon" href="static/appicons/favicon.ico"
             type="image/x-icon">';
    }
    ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.js"></script>
    <?php
    function pokemonFilterImages( $noPokemonNumbers, $onClick = '', $pokemonToExclude = array(), $num = 0 ) {
        global $mons, $copyrightSafe, $iconRepository;
        if ( empty( $mons ) ) {
            $json = file_get_contents( 'static/dist/data/pokemon.min.json' );
            $mons = json_decode( $json, true );
        }
        echo '<div class="pokemon-list-cont" id="pokemon-list-cont-' . $num . '"><input type="hidden" class="search-number" value="' . $num . '" /><input class="search search-input" placeholder="' . i8ln( "Search Name, ID & Type" ) . '" /><div class="pokemon-list list">';
        $i = 0;
        $z = 0;
        foreach ( $mons as $k => $pokemon ) {
            $type = '';
            $name = $pokemon['name'];
            foreach ( $pokemon['types'] as $t ) {
                $type .= $t['type'];
            }

            if ( ! in_array( $k, $pokemonToExclude ) ) {
                if ( $k > 493 ) {
                    break;
		}
		if ( $k <= 9 ) {
                    $id = "00$k";
                } else if ( $k <= 99 ) {
                    $id = "0$k";
                } else {
                    $id = $k;
		}
		if ( ! $copyrightSafe ) {
                    echo '<span class="pokemon-icon-sprite" data-value="' . $k . '" onclick="' . $onClick . '"><span style="display:none" class="types">' . i8ln( $type ) . '</span><span style="display:none" class="name">' . i8ln( $name ) . '</span><span style="display:none" class="id">$k</span><img src="' . $iconRepository . 'pokemon_icon_' . $id . '_00.png" style="width:48px;height:48px;"/>';
		} else {
                    echo '<span class="pokemon-icon-sprite" data-value="' . $k . '" onclick="' . $onClick . '"><span style="display:none" class="types">' . i8ln( $type ) . '</span><span style="display:none" class="name">' . i8ln( $name ) . '</span><span style="display:none" class="id">$k</span><img src="static/icons-safe/pokemon_icon_' . $id . '_00.png" style="width:48px;height:48px;"/>';
                }
                if ( ! $noPokemonNumbers ) {
                    echo "<span class='pokemon-number'>" . $k . "</span>";
                }
                echo "</span>";

            }
        }
        echo '</div></div>';
        ?>
        <script>
            var options = {
                valueNames: ['name', 'types', 'id']
            };
            var monList = new List('pokemon-list-cont-<?php echo $num;?>', options);
        </script>
        <?php
    }

    function itemFilterImages( $noItemNumbers, $onClick = '', $itemsToExclude = array(), $num = 0 ) {
        global $items, $copyrightSafe, $iconRepository;
        if ( empty( $items ) ) {
            $json = file_get_contents( 'static/dist/data/items.min.json' );
            $items = json_decode( $json, true );
        }
        echo '<div class="item-list-cont" id="item-list-cont-' . $num . '"><input type="hidden" class="search-number" value="' . $num . '" /><input class="search search-input" placeholder="' . i8ln( "Search Name & ID" ) . '" /><div class="item-list list">';
        $i = 0;
        $z = 0;
        foreach ( $items as $k => $item ) {
            $name = $item['name'];

            if ( ! in_array( $k, $itemsToExclude ) ) {
		if ( ! $copyrightSafe ) {
                    echo '<span class="item-icon-sprite" data-value="' . $k . '" onclick="' . $onClick . '"><span style="display:none" class="name">' . i8ln( $name ) . '</span><span style="display:none" class="id">$k</span><img src="' . $iconRepository . 'rewards/reward_' . $k . '_1.png" style="width:48px;height:48px;"/>';
		} else {
                    echo '<span class="item-icon-sprite" data-value="' . $k . '" onclick="' . $onClick . '"><span style="display:none" class="name">' . i8ln( $name ) . '</span><span style="display:none" class="id">$k</span><img src="static/icons-safe/rewards/reward_' . $k . '_1.png" style="width:48px;height:48px;"/>';
                }
                if ( ! $noItemNumbers ) {
                    echo '<span class="item-number">' . $k . '</span>';
                }
                echo "</span>";

            }
        }
        echo '</div></div>';
        ?>
        <script>
            var options = {
                valueNames: ['name', 'id']
            };
            var itemList = new List('item-list-cont-<?php echo $num;?>', options);
        </script>
        <?php
    }

    ?>

    <?php
    if ( $gAnalyticsId != "" ) {
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
    if ( $piwikUrl != "" && $piwikSiteId != "" ) {
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
        var token = '<?php echo ( ! empty( $_SESSION['token'] ) ) ? $_SESSION['token'] : ""; ?>';
    </script>
    <link href="node_modules/leaflet-geosearch/assets/css/leaflet.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.css">
    <link rel="stylesheet" href="node_modules/datatables/media/css/jquery.dataTables.min.css">
    <script src="static/js/vendor/modernizr.custom.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Leaflet -->
    <link rel="stylesheet" href="node_modules/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="static/dist/css/app.min.css">
    <link rel="stylesheet" href="node_modules/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="node_modules/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <link href='static/css/leaflet.fullscreen.css' rel='stylesheet' />
	<!-- font awesome icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body id="top">
<div class="wrapper">
    <!-- Header -->
    <header id="header" style = "background-image: linear-gradient(to top, #686868 0%, #121212 100%)">
        <a href="#nav"><b><span class="label" style="color:white"><?php echo i8ln('Menü') ?></span></b></a>

        <h1><a href="#"><?= $title ?><img src="<?= $raidmapLogo ?>" height="35" width="auto" border="0" style="float: right; margin-left: 5px; margin-top: 10px;"></a></h1>
        <?php
        if ( $discordUrl != "" ) {
            echo '<a href="' . $discordUrl . '" target="_blank" style="margin-bottom: 5px; vertical-align: middle;padding:0 5px;">
            <img src="static/images/discord_big.png" border="0" style="float: right; width: 36px; height: auto;">
        </a>';
        }
        if ( $paypalUrl != "" ) {
            echo '<a href="' . $paypalUrl . '" target="_blank" style="margin-bottom: 5px; vertical-align: middle; padding:0 5px;">
            <img src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_74x21.png" border="0" name="submit"
                 title="PayPal - The safer, easier way to pay online!" alt="Donate" style="float: right;">
        </a>';
        }
        ?>
        <?php if ( ! $noWeatherOverlay ) {
            ?>
            <div id="currentWeather"></div>
            <?php
        } ?>
        
        <?php
        if ($noNativeLogin === false || $noDiscordLogin === false) {
            if (isset($_COOKIE["LoginCookie"])) {
                if (validateCookie($_COOKIE["LoginCookie"]) === false) {
                    header("Location: .");
                }
            }
            if (!empty($_SESSION['user']->id)) {
                $info = $manualdb->query(
                    "SELECT expire_timestamp FROM users WHERE id = :id AND login_system = :login_system", [
                        ":id" => $_SESSION['user']->id,
                        ":login_system" => $_SESSION['user']->login_system
                    ]
                )->fetch();

                $_SESSION['user']->expire_timestamp = $info['expire_timestamp'];

			if (($noNativeLogin === false || $noDiscordLogin === false) && $info['expire_timestamp'] > time()) {
				//If the session variable does not exist, presume that user suffers from a bug and access config is not used.
				//If you don't like this, help me fix it.
				if (!isset($_SESSION['already_refreshed'])) {
			
					//Number of seconds to refresh the page after.
					$refreshAfter = 1;
			
					//Send a Refresh header.
					header('Refresh: ' . $refreshAfter);
			
					//Set the session variable so that we don't refresh again.
					$_SESSION['already_refreshed'] = true; 
				}
			}
				
                if (!empty($_SESSION['user']->updatePwd) && $_SESSION['user']->updatePwd === 1) {
                    header("Location: ./user");
                    die();
                }
                
                if ($info['expire_timestamp'] > time()) {
                    $color = "green";
                } else {
                    header('Location: ./logout.php');
                }
				$userAccessLevel = $manualdb->get( "users", [ 'access_level' ], [ 'expire_timestamp' => $_SESSION['user']->expire_timestamp ] );
				if ($userAccessLevel['access_level'] == 1) {
				echo "<span style='color: ". $color .";'><i class='fa fa-check fa-fw'></i></span>";
				} 
				elseif ($userAccessLevel['access_level'] == 0) {
				echo "<span style='color: yellow;'><i class='fa fa-check fa-fw'></i></span>";
				}
				else{
				echo "<span style='color: red;'><i class='fa fa-times fa-fw'></i></span>";
				}
				

                //echo "<span style='color: {$color};'>" . substr($_SESSION['user']->user, 0, 3) . "...</span>";
            } else {
                echo "<a href='./user'> Login </a>";
            }
        }
        ?>
        <?php if ( ! $noStatsToggle ) {
            ?>
        <a href="#stats" id="statsToggle" class="statsNav" style="float: right;"><span
                class="label"><?php echo i8ln( 'Stats' ) ?></span></a>
            <?php
        } ?>
    </header>
    <!-- NAV -->
    <nav id="nav">
        <div id="nav-accordion">
            <?php
            if ( ! $noPokemon || ! $noNests ) {
                if ( ! $noNests && ! $noPokemon) {
                ?>
                <h3 style="font-weight: bold;"><i class="fa fa-map-marker fa-fw"></i>&nbsp;Pokemon & Nester</h3>
                <?php
                } else if (!$noNests){ 
                ?>
                <h3 style="font-weight: bold;"><i class="fa fa-map-marker fa-fw"></i>&nbsp;Nester</h3>
                <?php
                } else if (!$noPokemon){ 
				?>
                <h3 style="font-weight: bold;"><i class="fa fa-map-marker fa-fw"></i>&nbsp;Pokemon</h3>
                <?php
				}
				?>
                <div>
                <?php
                if ( ! $noNests ) {
                    echo '<div class="form-control switch-container" style="float:none;height:35px;margin-bottom:0px;">
                    <h3> Nester </h3>
                    <div class="onoffswitch">
                        <input id="nests-switch" type="checkbox" name="nests-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="nests-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>
					<div id="nests-content-wrapper" style="display:none">
						<div>
							<center>
								<u><h3> Nester teilen (Whatsapp)<h3></u>
								<a class="settings" id="shareWhatsappNestsAll" href="#" data-action="share/whatsapp/share" style="background-color: #555555;border: 1px solid;border-color: black;color: white;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;padding: 6px 12px;border-radius: 16px;"
									onclick="shareNestsWhatsapp(\'all\')">
									<i class="fa fa-upload" aria-hidden="true"></i> Alle teilen
								</a><br><br>
								<a class="settings" id="shareWhatsappNestsBig" href="#" data-action="share/whatsapp/share" style="background-color: #555555;border: 1px solid;border-color: black;color: white;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;padding: 6px 12px;border-radius: 16px;"
									onclick="shareNestsWhatsapp(\'big\')">
									<i class="fa fa-upload" aria-hidden="true"></i> Nur Große teilen
								</a>
							</center>
						</div>
						<div>
						Beachte: Es werden nur die Nester geteilt, die auf deiner Map zu sehen sind.
						</div>
						
					</div>
					<br>
				
				';
                } ?>
                <?php
                if ( ! $noPokemon ) {
                    echo '<div class=" form-control switch-container" style="float:none;height:35px;margin-bottom:0px;">
                    <h3> Pokemon </h3>
                    <div class="onoffswitch">
                        <input id="pokemon-switch" type="checkbox" name="pokemon-switch" class="onoffswitch-checkbox"
                               checked>
                        <label class="onoffswitch-label" for="pokemon-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
				</div>';
                } ?>
                    <div id="pokemon-filter-wrapper" style="display:none">
                        <?php
                        if ( ! $noTinyRat ) {
                            ?>
                            <div class="form-control switch-container">
                                <font size="3"><?php echo i8ln( 'Tiny Rats' ) ?></font>
                                <div class="onoffswitch">
                                    <input id="tiny-rat-switch" type="checkbox" name="tiny-rat-switch"
                                           class="onoffswitch-checkbox" checked>
                                    <label class="onoffswitch-label" for="tiny-rat-switch">
                                        <span class="switch-label" data-on="On" data-off="Off"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <?php
                        if ( ! $noBigKarp ) {
                            ?>
                            <div class="form-control switch-container">
                                <font size="3"><?php echo i8ln( 'Big Karp' ) ?></font>
                                <div class="onoffswitch">
                                    <input id="big-karp-switch" type="checkbox" name="big-karp-switch"
                                           class="onoffswitch-checkbox" checked>
                                    <label class="onoffswitch-label" for="big-karp-switch">
                                        <span class="switch-label" data-on="On" data-off="Off"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <div class="form-row min-stats-row">
                            <?php
                            if ( ! $noMinIV ) {
                                echo '<div class="form-control" >
                            <label for="min-iv">
                                <h3> Min. IV</h3>
                                <input id="min-iv" type="number" min="0" max="100" name="min-iv" placeholder="' . i8ln( 'Min IV' ) . '"/>
                            </label>
                        </div>';
                            } ?>
                            <?php
                            if ( ! $noMinLevel ) {
                                echo '<div class="form-control">
                            <label for="min-level">
                                <h3> Min. Lvl</h3>
                                <input id="min-level" type="number" min="0" max="100" name="min-level" placeholder="' . i8ln( 'Min Lvl' ) . '"/>
                            </label>
                        </div></p>';
                            } ?>
                        </div>
                        <div id="tabs">
                            <ul>
                                <?php
                                if ( ! $noHidePokemon ) {
                                    ?>
                                    <li><a href="#tabs-1">Ausblenden</a></li>
                                    <?php
                                } ?>
                                <?php
                                if ( ! $noExcludeMinIV ) {
                                    ?>
                                    <li><a href="#tabs-2">Immer anzeigen</a></li>
                                    <?php
                                } ?>
                            </ul>
                            <?php
                            if ( ! $noHidePokemon ) {
                                ?>
                                <div id="tabs-1">
                                    <div class="form-control hide-select-2">
                                        <label for="exclude-pokemon">
                                            <div class="pokemon-container">
                                                <input id="exclude-pokemon" type="text" readonly="true">
                                                <?php
                                                pokemonFilterImages( $noPokemonNumbers, '', [], 2 ); ?>
                                            </div>
                                            <a href="#" class="select-all">Alle
                                                <div>
                                            </a><a href="#" class="hide-all">Keine</a>
                                        </label>
                                    </div>
                                </div>
                                <?php
                            } ?>
                            <?php
                            if ( ! $noExcludeMinIV ) {
                                ?>
                                <div id="tabs-2">
                                    <div class="form-control hide-select-2">
                                        <label for="exclude-min-iv">
                                            <div class="pokemon-container">
                                                <input id="exclude-min-iv" type="text" readonly="true">
                                                <?php
                                                pokemonFilterImages( $noPokemonNumbers, '', [], 3 ); ?>
                                            </div>
                                            <a href="#" class="select-all">Alle
                                                <div>
                                            </a><a href="#" class="hide-all">Keine</a>
                                        </label>
                                    </div>
                                </div>
                                <?php
                            } ?>
                        </div>
                    </div>
					<?php
					if(!$noPokemon){
					echo 'Blende mehr Pokemon aus, um die Performance der Ladezeit zu erhöhen.<br><br>';
					} ?>
					<?php
					if( !$noHighLevelData){
					echo 'Unter \'Immer anzeigen\' kannst du Pokemon auswählen, die trotz IV/Lvl Filter dennoch angezeigt werden sollen.';
					} ?>
                </div>
                <?php
            }
            ?>
            <?php
            if ( ! $noPokestops ) {
                if ( ! $noQuests ) {
                ?>
		<h3 style="font-weight: bold"><i class="fa fa-map-pin fa-fw"></i>&nbsp;Pokestops &amp; Quests</h3>
                <?php
                } else {
                ?>
		<h3 style="font-weight: bold"><i class="fa fa-map-pin fa-fw"></i>&nbsp;Stops</h3>
                <?php
                } ?>
		<div>
                <?php
                if ( ! $noPokestops ) {
                    echo '<div class="form-control switch-container" style="float:none;height:35px;margin-bottom:0px;">
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
		} ?>
                    <div id="pokestops-filter-wrapper" style="display:none">
                <?php
                if ( ! $noLures ) {
                    echo '<div class="form-control switch-container" style="float:none;height:35px;margin-bottom:0px;">
                    <font size="3">Nur Lockmodule</font>
                    <div class="onoffswitch">
                        <input id="lures-switch" type="checkbox" name="lures-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="lures-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
		} ?>
                <?php
                if ( ! $noQuests ) {
                    echo '<div class="form-control switch-container" style="float:none;height:35px;margin-bottom:0px;">
                    <font size="3">Nur Quests</font>
                    <div class="onoffswitch">
                        <input id="quests-switch" type="checkbox" name="quests-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="quests-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
		?>
                    <div id="quests-filter-wrapper" style="display:none">
                        <div id="quests-tabs">
                            <ul>
                                <?php
                                if ( ! $noQuestsPokemon ) {
                                    ?>
                                    <li><a href="#tabs-1">Pokemon-Filter</a></li>
                                    <?php
                                } ?>
                                <?php
                                if ( ! $noQuestsItems ) {
                                    ?>
                                    <li><a href="#tabs-2">Item-Filter</a></li>
                                    <?php
                                } ?>
	                    </ul>
                            <?php
                            if ( ! $noQuestsPokemon ) {
                                ?>
                                <div id="tabs-1">
                                    <div class="form-control hide-select-2">
                                        <label for="exclude-quests-pokemon">
                                            <div class="quest-pokemon-container">
                                                <input id="exclude-quests-pokemon" type="text" readonly="true">
                                                <?php
                                                pokemonFilterImages( $noPokemonNumbers, '', $excludeQuestsPokemon, 8 ); ?>
                                            </div>
                                            <a href="#" class="select-all">Alle
                                                <div>
                                            </a><a href="#" class="hide-all">Keine</a>
                                        </label>
                                    </div>
                                </div>
                                <?php
                            } ?>
                            <?php
                            if ( ! $noQuestsItems ) {
                                ?>
                                <div id="tabs-2">
                                    <div class="form-control hide-select-2">
                                        <label for="exclude-quests-item">
                                            <div class="quest-item-container">
                                                <input id="exclude-quests-item" type="text" readonly="true">
                                                <?php
                                                itemFilterImages( $noItemNumbers, '', $excludeQuestsItem, 9 ); ?>
                                            </div>
                                            <a href="#" class="select-all-item">Alle
                                                <div>
                                            </a><a href="#" class="hide-all-item">Keine</a>
                                        </label>
                                    </div>
                                </div>
                                <?php
                            } ?>
                        </div>
                        <div class="dustslider">
			    <input type="range" min="0" max="2000" value="500" class="slider" id="dustrange">
			    <p>Min. Sternenstaub: <span id="dustvalue"></span></p>
                        </div>
                    </div>
                <?php
		} ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
            if ( ! $noCommunity ) {
                ?>
                <h3><?php echo i8ln( 'Communities' ); ?></h3>
		<div>
                <?php
                if ( ! $noCommunity ) {
                    echo '<div class="form-control switch-container">
                    <h3>' . i8ln( 'Communities' ) . '</h3>
                    <div class="onoffswitch">
                        <input id="communities-switch" type="checkbox" name="communities-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="communities-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
		} ?>
                </div>
                <?php
            }
            ?>
            <?php
            if ( ! $noRaids || ! $noGyms ) {
                ?>
				
				<h3 style="font-weight: bold"><i class="fa fa-shield fa-fw"></i>&nbsp;Arenen &amp; Raids</h3>
				
				
                <div>
                    <?php
                    if ( ! $noRaids ) {
                        echo '<div class="form-control switch-container" id="raids-wrapper">
                    <h3>' . i8ln( 'Raids' ) . '</h3>
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
					
					<?php
					if (!$noGymTeamInfos){
						echo '
						<div id="raids-filter-wrapper" style="display:none">
							<div class="form-control switch-container" id="active-raids-wrapper">
								<font size="3">Nur aktive Raids</font>
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
								<font size="3">Min. Raid Level</font>
								<select name="min-level-raids-filter-switch" id="min-level-raids-filter-switch">
									<option value ="">Einstellung angeben...</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</div>
							<div class="form-control switch-container" id="max-level-raids-filter-wrapper">
								<font size="3">Max. Raid Level</font>
								<select name="max-level-raids-filter-switch" id="max-level-raids-filter-switch">
									<option value ="">Einstellung angeben...</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</div>
						</div>
						
						';
					} ?>
                    <?php
                    if ( ! $noGymSidebar && ( ! $noGyms || ! $noRaids ) ) {
                        echo '<div id="gym-sidebar-wrapper" class="form-control switch-container">
                    <font size="3">' . i8ln( 'Use Gym Sidebar' ) . '</font>
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
                    if ( ! $noGyms ) {
                        echo '<div class="form-control switch-container">
                    <h3>' . i8ln( 'Gyms' ) . '</h3>
                    <div class="onoffswitch">
                        <input id="gyms-switch" type="checkbox" name="gyms-switch" class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="gyms-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
		    } ?>
                    <?php
                    if ( ! $hideIfManual && !$noGymTeamInfos) {
                        echo '<div id="gyms-filter-wrapper" style="display:none">
                        <div class="form-control switch-container" id="team-gyms-only-wrapper">
                            <font size="3">Team</font>
                            <select name="team-gyms-filter-switch" id="team-gyms-only-switch">
                                <option value="0"> Alle</option>
                                <option value="1"> Mystic / Weisheit</option>
                                <option value="2"> Valor / Wagemut</option>
                                <option value="3"> Instinct / Intuition</option>
                            </select>
						</div>
                        <div class="form-control switch-container" id="open-gyms-only-wrapper">
                            <font size="3">Freie Plätze</font>
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
                            <font size="3">Min. freie Plätze</font>
                            <select name="min-level-gyms-filter-switch" id="min-level-gyms-filter-switch">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                        <div class="form-control switch-container" id="max-level-gyms-filter-wrapper">
                            <font size="3">Max. freie Plätze</font>
                            <select name="max-level-gyms-filter-switch" id="max-level-gyms-filter-switch">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                        <div class="form-control switch-container" id="last-update-gyms-wrapper">
                            <font size="3">Letzter Scan</font>
                            <select name="last-update-gyms-switch" id="last-update-gyms-switch">
                                <option value="0">Alle</option>
                                <option value="1">Letzte Stunde</option>
                                <option value="6">Letzten 6 Stunden</option>
                                <option value="12">Letzten 12 Stunden</option>
                                <option value="24">Letzten 24 Stunden</option>
                                <option value="168">Letzte Woche</option>
                            </select>
                        </div>
		    </div>';
                    }
                    ?>
                    <div id="gyms-raid-filter-wrapper" style="display:none">
                        <?php
                        if ( ( $fork === "alternate" || $map === "rdm" || ( $map === "rm" && $fork !== "sloppy" ) ) && ! $noExEligible ) {
                            echo '<div class="form-control switch-container" id="ex-eligible-wrapper">
                                <font size="3">' . i8ln( 'EX Eligible Only' ) . '</font>
                                <div class="onoffswitch">
                                    <input id="ex-eligible-switch" type="checkbox" name="ex-eligible-switch"
                                           class="onoffswitch-checkbox" checked>
                                    <label class="onoffswitch-label" for="ex-eligible-switch">
                                        <span class="switch-label" data-on="On" data-off="Off"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>
                            </div>';
                        }
                        if ( ! $noBattleStatus ) {
                            echo '<div class="form-control switch-container" id="battle-status-wrapper">
                                <font size="3">' . i8ln( 'Nur Arenen im Kampf' ) . '</font>
                                <div class="onoffswitch">
                                    <input id="battle-status-switch" type="checkbox" name="battle-status-switch"
                                           class="onoffswitch-checkbox" checked>
                                    <label class="onoffswitch-label" for="battle-status-switch">
                                        <span class="switch-label" data-on="On" data-off="Off"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>
                            </div>';
                        }
						?>
                    </div>
						<?php
						if($onlyTriggerGyms){
						echo '(Bereits getriggerte Arenen werden mit einem "EX" versehen)';
						} ?>
                </div>
                <?php
            }
            ?>
            <?php
            if ( ! $noPortals || ! $noS2Cells ) {
                ?>
                <h3><?php echo i8ln( 'Ingress / S2Cell' ); ?></h3>
		<div>
                <?php
                if ( ! $noPortals ) {
                    echo '<div class="form-control switch-container">
                    <h3>' . i8ln( 'Portals' ) . '</h3>
                    <div class="onoffswitch">
                        <input id="portals-switch" type="checkbox" name="portals-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="portals-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
		</div>
                <div class="form-control switch-container" id = "new-portals-only-wrapper" style = "display:none">
                    <select name = "new-portals-only-switch" id = "new-portals-only-switch">
                        <option value = "0"> ' . i8ln( 'All' ) . '</option>
                        <option value = "1"> ' . i8ln( 'Only new' ) . ' </option>
                    </select>
                </div>';
		} ?>
                <?php
                if ( ! $noPoi ) {
                    echo '<div class="form-control switch-container">
                    <h3>' . i8ln( 'POI' ) . '</h3>
                    <div class="onoffswitch">
                        <input id="poi-switch" type="checkbox" name="poi-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="poi-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
		} ?>
                <?php
                if ( ! $noS2Cells ) {
                    echo '<div class="form-control switch-container">
                    <h3>' . i8ln( 'Show S2 Cells' ) . '</h3>
                    <div class="onoffswitch">
                        <input id="s2-switch" type="checkbox" name="s2-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="s2-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
		</div>
                <div class="form-control switch-container" id = "s2-switch-wrapper" style = "display:none">
                    <div class="form-control switch-container">
                        <h3>' . i8ln( 'EX trigger Cells' ) . '</h3>
                        <div class="onoffswitch">
                            <input id="s2-level13-switch" type="checkbox" name="s2-level13-switch"
                                   class="onoffswitch-checkbox" checked>
                            <label class="onoffswitch-label" for="s2-level13-switch">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
			</div>
                    </div>
                    <div class="form-control switch-container">
                        <h3>' . i8ln( 'Gym placement Cells' ) . '</h3>
                        <div class="onoffswitch">
                            <input id="s2-level14-switch" type="checkbox" name="s2-level14-switch"
                                   class="onoffswitch-checkbox" checked>
                            <label class="onoffswitch-label" for="s2-level14-switch">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-control switch-container">
                        <h3>' . i8ln( 'Pokestop placement Cells' ) . '</h3>
                        <div class="onoffswitch">
                            <input id="s2-level17-switch" type="checkbox" name="s2-level17-switch"
                                   class="onoffswitch-checkbox" checked>
                            <label class="onoffswitch-label" for="s2-level17-switch">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                </div>';
		} ?>
                </div>
                <?php
            }
            ?>
            <?php
            if ( ! $noSearchLocation || ! $noNests || ! $noStartMe || ! $noStartLast || ! $noFollowMe || ! $noPokestops || ! $noScannedLocations || ! $noSpawnPoints || ! $noRanges || ! $noWeatherOverlay || ! $noSpawnArea || ! $noScanPolygon || ! $noScanPolygonQuest || ! $noScanPolygonPvp) {
                if ( ! $noScanPolygon || ! $noScanPolygonQuest || ! $noScanPolygonPvp ) {
                echo '<h3 style="font-weight: bold"><i class="fa fa-location-arrow fa-fw"></i>&nbsp;Location &amp; Gebiete</h3>
                    <div>';
                } else {
                echo '<h3 style="font-weight: bold"><i class="fa fa-location-arrow fa-fw"></i>&nbsp;Location</h3>
                    <div>';
		} ?>
                <?php
                if ( $map != "monocle" && ! $noScannedLocations ) {
                    echo '<div class="form-control switch-container">
                    <h3> ' . i8ln( 'Scanned Locations' ) . ' </h3>
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
                if ( ! $noWeatherOverlay ) {
                    echo '<div class="form-control switch-container">
                    <h3> ' . i8ln( 'Weather Conditions' ) . ' </h3>
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
                if ( ! $noSpawnPoints ) {
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
                } ?>
                <?php
                if ( ! $noRanges ) {
                    echo '<div class="form-control switch-container">
                    <h3> Reichweiten </h3>
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
                if ( ! $noSearchLocation ) {
                    echo '<div class="form-control switch-container" style="display:{{is_fixed}}">
                <label for="next-location">
		    <h3>Suchstandort ändern:</h3>
                    <form id ="search-places">
		    <input id="next-location" type="text" name="next-location" placeholder="Adresse suchen..">
                    <ul id="search-places-results" class="search-results places-results"></ul>
                    </form>
                </label>
            </div>';
                } ?>
                <?php
                if ( ! $noStartMe ) {
                    echo '<div class="form-control switch-container">
                    <h3> Starte an meiner Position </h3>
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
                if ( ! $noStartLast ) {
                    echo '<div class="form-control switch-container">
                    <h3> Starte an letzter Position </h3>
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
                if ( ! $noFollowMe ) {
                    echo '<div class="form-control switch-container">
                    <h3> Mir folgen </h3>
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
                if ( ! $noSpawnArea ) {
                    echo '<div id="spawn-area-wrapper" class="form-control switch-container">
                <font size="3"> ' . i8ln( 'Spawn area' ) . ' </font>
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
				if ( ! $noScanPolygonQuest || ! $noScanPolygonPvp || ! $noScanPolygon){
					echo '<div>
						<h3><center><u> Gebiete </u></center></h3>
					</div>';
				} ?>
                <?php
				
                if ( ! $noScanPolygon ) {
                    echo '
					<div class="form-control switch-container">
                    <h3> Scangebiet (Pkmn,Arenen,Stops,Raids) </h3>
						<div class="onoffswitch">
							<input id="scan-area-switch" type="checkbox" name="scan-area-switch" class="onoffswitch-checkbox">
							<label class="onoffswitch-label" for="scan-area-switch">
								<span class="switch-label" data-on="On" data-off="Off"></span>
								<span class="switch-handle"></span>
							</label>
						</div>
					</div>
					';
                } ?>
                <?php

                if ( ! $noScanPolygonQuest ) {
                    echo '
					<div class="form-control switch-container">
                    <h3> Scangebiet (Quests) </h3>
						<div class="onoffswitch">
							<input id="scan-area-quest-switch" type="checkbox" name="scan-area-quest-switch" class="onoffswitch-checkbox">
							<label class="onoffswitch-label" for="scan-area-quest-switch">
								<span class="switch-label" data-on="On" data-off="Off"></span>
								<span class="switch-handle"></span>
							</label>
						</div>
					</div>
					';
                } ?>
                <?php
                if ( ! $noScanPolygonPvp ) {
                    echo '
					<div class="form-control switch-container">
                    <h3> Pvp-Gebiete </h3>
						<div class="onoffswitch">
							<input id="scan-area-pvp-switch" type="checkbox" name="scan-area-pvp-switch" class="onoffswitch-checkbox">
							<label class="onoffswitch-label" for="scan-area-pvp-switch">
								<span class="switch-label" data-on="On" data-off="Off"></span>
								<span class="switch-handle"></span>
							</label>
						</div>
					</div>
					';
                } ?>
                <?php
                echo '</div>';
            }
            ?>
            <?php
            if ( ! $noNotifyPokemon || ! $noNotifyRarity || ! $noNotifyIv || ! $noNotifyLevel || ! $noNotifySound || ! $noNotifyRaid || ! $noNotifyBounce || ! $noNotifyNotification ) {
                echo '<h3 style="font-weight: bold"><i class="fa fa-star fa-fw"></i>&nbsp;Favoriten</h3>
            <div>';
            }
            ?>
            <?php
            if ( ! $noNotifyPokemon ) {
                echo '<div class="form-control hide-select-2">
                    <label for="notify-pokemon">
                        <h3>Meldungen für Pokemon</h3><a href="#" class="select-all" style="background-color:#3b3b3b;border-radius:3px;padding: 5px 10px;border-color: white;color:white">Alle</a>&nbsp;&nbsp;<a href="#" class="hide-all" style="background:#3b3b3b;border-radius:3px;padding: 5px 10px;border-color: white;color:white">Keine</a><br><br>
                        <div style="max-height:165px;overflow-y:auto;">
                            <input id="notify-pokemon" type="text" readonly="true"/>';
                pokemonFilterImages( $noPokemonNumbers, '', [], 4 );
                echo '</div>
                    </label>
                </div>';
            }
            ?>
            <?php
            if ( ! $noNotifyRarity ) {
                echo '<div class="form-control">
                <label for="notify-rarity">
                    <h3>' . i8ln( 'Notify of Rarity' ) . '</h3>
                    <div style="max-height:165px;overflow-y:auto">
                        <select id="notify-rarity" multiple="multiple"></select>
                    </div>
                </label>
            </div>';
            }
            ?>
            <?php
            if ( ! $noNotifyIv ) {
                echo '<div class="form-control">
                <label for="notify-perfection">
                    <h3>Fav. nach IV</h3>
                    <input id="notify-perfection" type="text" name="notify-perfection"
                           placeholder="Min %" style="float: right;width: 75px;text-align:center"/>
                </label>
            </div>';
            }
            ?>
            <?php
            if ( ! $noNotifyLevel ) {
                echo '<div class="form-control">
                <label for="notify-level">
                    <h3 style="float:left;">Fav. nach Level</h3>
                    <input id="notify-level" min="1" max="35" type="number" name="notify-level"
                           placeholder="Min Lvl" style="float: right;width: 75px;text-align:center"/>
                </label>
            </div>';
            }
            ?>
            <?php
            if ( ! $noNotifyRaid ) {
                echo '<div class="form-control switch-container" id="notify-raid-wrapper">
                        <h3>Fav. min. Raid Lvl:</h3>
                        <select name="notify-raid" id="notify-raid">
                            <option value="0">Deaktiviert</option>
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
            if ( ! $noNotifySound ) {
                echo '<div class="form-control switch-container">
                <h3>Meldung mit Ton</h3>
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
            if ( ! $noCriesSound ) {
                echo '<div class="form-control switch-container" id="cries-switch-wrapper">
                <h3>' . i8ln( 'Use Pokemon cries' ) . '</h3>
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
            if ( ! $noNotifySound ) {
                echo '</div>';
            }
            ?>
            <?php
            if ( ! $noNotifyBounce ) {
                echo '<div class="form-control switch-container">
                <h3>Fav. Springen</h3>
                <div class="onoffswitch">
                    <input id="bounce-switch" type="checkbox" name="bounce-switch" class="onoffswitch-checkbox"
                           checked>
                    <label class="onoffswitch-label" for="bounce-switch">
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>';
            }
            ?>
            <?php
            if ( ! $noNotifyNotification ) {
                echo '<div class="form-control switch-container">
                <h3>Push Nachrichten</h3>
                <div class="onoffswitch">
                    <input id="notification-switch" type="checkbox" name="notification-switch" class="onoffswitch-checkbox"
                           checked>
                    <label class="onoffswitch-label" for="notification-switch">
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>';
            }
            ?>
            <?php
            if ( ! $noNotifyPokemon || ! $noNotifyRarity || ! $noNotifyIv || ! $noNotifyLevel || ! $noNotifySound || ! $noNotifyRaid || ! $noNotifyBounce || ! $noNotifyNotification ) {
                echo '</div>';
            }
            ?>

            <?php
            if ( ! $noMapStyle || ! $noDirectionProvider || ! $noIconSize || ! $noIconNotifySizeModifier || ! $noGymStyle || ! $noLocationStyle ) {
                echo '<h3 style="font-weight: bold"><i class="fa fa-map-o fa-fw"></i>&nbsp;Style</h3>
            <div>';
            }
            ?>
            <?php
            if ( ! $noMapStyle ) {
                echo '<div class="form-control switch-container">
                <h3>Map Style</h3>
                <select id="map-style"></select>
            </div>';
            }
            ?>
            <?php
            if ( ! $noDirectionProvider ) {
                echo '<div class="form-control switch-container">
                <h3>Navigation über:</h3>
                <select name="direction-provider" id="direction-provider">
                    <option value="apple">' . i8ln( 'Apple' ) . '</option>
                    <option value="google">' . i8ln( 'Google (Directions)' ) . '</option>
                    <option value="google_pin">' . i8ln( 'Google (Pin)' ) . '</option>
                    <option value="waze">' . i8ln( 'Waze' ) . '</option>
                    <option value="bing">' . i8ln( 'Bing' ) . '</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if ( ! $noIconSize ) {
                echo '<div class="form-control switch-container">
                <h3>Icon Größe</h3>
                <select name="pokemon-icon-size" id="pokemon-icon-size">
                    <option value="-8">' . i8ln( 'Small' ) . '</option>
                    <option value="0">' . i8ln( 'Normal' ) . '</option>
                    <option value="10">' . i8ln( 'Large' ) . '</option>
                    <option value="20">' . i8ln( 'X-Large' ) . '</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if ( ! $noIconNotifySizeModifier ) {
                echo '<div class="form-control switch-container">
                <h3>Fav. Vergrößerung</h3>
                <select name="pokemon-icon-notify-size" id="pokemon-icon-notify-size">
                    <option value="0">' . i8ln( 'Disable' ) . '</option>
                    <option value="15">' . i8ln( 'Large' ) . '</option>
                    <option value="30">' . i8ln( 'X-Large' ) . '</option>
                    <option value="45">' . i8ln( 'XX-Large' ) . '</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if ( ! $noGymStyle ) {
                echo '<div class="form-control switch-container">
                <h3>Arenen Style</h3>
                <select name="gym-marker-style" id="gym-marker-style">
                    <option value="classic">Classic</option>
                    <option value="shield">Schilder</option>
                    <option value="beasts">Biester</option>
                    <option value="idol">Idol</option>
                    <option value="elements">Elemente</option>
                    <option value="ingame">Standard</option>
                </select>
            </div>
			';
			}
			?>
            <?php
            if ( ! $noLocationStyle ) {
                echo '<div class="form-control switch-container">
                <h3>Standort Style</h3>
                <select name="locationmarker-style" id="locationmarker-style"></select>
            </div>';
            }
            ?>
            <?php
            if ( ! $noMapStyle || ! $noDirectionProvider || ! $noIconSize || ! $noIconNotifySizeModifier || ! $noGymStyle || ! $noLocationStyle ) {
                echo '</div>';
            }
            ?>
			<?php
			if (!$noExportImport){
				echo '
				<h3 style="font-weight: bold"><i class="fa fa-sliders fa-fw"></i>&nbsp;Einstellungen</h3>
				<div>
					<span style="color: #3b3b3b"><b style="font-size:17px">Zurücksetzen:</b><br>Alle Einstellungen des Menüs werden auf Standard zurückgesetzt.</span>
					<div>
						<center>
							<button class="settings"
									onclick="confirm(\'Möchtest du die Einstellungen auf Standard zurücksetzen?\') ? (localStorage.clear(), window.location.reload()) : false">
								<i class="fa fa-refresh" aria-hidden="true"></i> Zurücksetzen
							</button>
						</center>
					</div>
					<br>
					<span style="color: #3b3b3b"><b style="font-size:17px">Exportieren:</b><br>Speichere deine Einstellungen des Menüs indem du sie als Datei downloadest.<br></span>
					<span style="color: #3b3b3b"><b style="font-size:17px">Importieren:</b><br>Lade eine Datei hoch, um zuvor gespeicherte Einstellungen wieder herzustellen.</span>
					<div>
						<center>
							<button class="settings"
								onclick="download(\''. addslashes( $title ) .'\', JSON.stringify(JSON.stringify(localStorage)))">
								<i class="fa fa-upload" aria-hidden="true"></i> Exportieren
							</button>
						</center>
					</div>
					<div>
						<center>
							<input id="fileInput" type="file" style="display:none;" onchange="openFile(event)"/>
							<button class="settings"
									onclick="document.getElementById(\'fileInput\').click()">
								<i class="fa fa-download" aria-hidden="true"></i> Importieren
							</button>
						</center>
					</div>
				</div>';
			}?>
			
			<?php
			if (!$noQuests && !$noPokemon && $infopageUrl == "https://rocketmapdo.de/infopage/" ){
                echo '<h3 style="font-weight: bold"><i class="fa fa-clock-o fa-fw"></i>&nbsp;Scanzeiten</h3>
				<div>
					<p style="height:30px"><img src="static/forts/Pstop-quest-small.png" alt ="" style="height:30px;width: auto;float:left"/><b style="font-size:17px">00:00-05:30 :</b>  Questscan</p> 
					<p style="height:30px"><img src="static/icons/pokemon_icon_025_00.png" alt ="" style="height:30px;width: auto;float:left"/><b style="font-size:17px">05:30-23:59 :</b>  Pokemon & IV </p> 
					<br>
					Während <b>Quests</b> gescannt werden, werden Pokemon nicht auf IV gescannt. Pokemon werden nur dort gescannt, wo der Scanner auch gerade Quests scannt.
					<br>
					<br>
					Während der <b>Pokemon & IV-Scan</b> aktiv ist wird der Dortmunder Scanradius wie gewöhnlich auf Pokemon sowie ausgewählte Pokemon auf IV gescannt.
				</div>';
			}
			?>

			
            <?php
			if (($noDiscordLogin === false) && !empty($_SESSION['user']->id)) {
                echo '<h3 style="font-weight: bold"><i class="fa fa-key fa-fw"></i>&nbsp;Authentifizierung</h3>
            <div>';
            ?>
            <div><center><p>
            <?php
            echo '<b>Eingeloggt:</b> via Discord<br>
			<b>User:</b> ' . $_SESSION['user']->user . "";
            ?>
			</p></center></div>
			<div>
                <center>
                    <button class="settings"
                            onclick="document.location.href='logout.php'">
                        <i class="fa" aria-hidden="true"></i> Logout
                    </button>
                </center>
            </div><br>
        <?php
		echo '</div>';
        }
        ?>
        <?php
            if ( ! $noAreas ) {
			echo '<h3 style="font-weight: bold"><i class="fa fa-globe fa-fw"></i>&nbsp;Orte</h3>';
                $count = sizeof( $areas );
                if ( $count > 0 ) {
                    echo '<div class="form-control switch-container area-container"><ul>';
                    for ( $i = 0; $i <= $count - 1; $i ++ ) {
                        echo '<li><a href="" data-lat="' . $areas[ $i ][0] . '" data-lng="' . $areas[ $i ][1] . '" data-zoom="' . $areas[ $i ][2] . '" class="area-go-to">' . $areas[ $i ][3] . '</a></li>';
                    }
                    echo '</ul></div>';
                }
            }
        ?>
        </div>
        <?php
        if ( $infopageUrl != "" ) {
            echo '<p><center><a href="' . $infopageUrl . '" target="_blank" style="background-color: #555555;border: 1px solid;border-color: black;color: white;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;padding: 6px 12px;border-radius: 16px;">
            <i class="fa fa-info-circle fa-fw"></i>Unsere Infopage
        </a></center></p>';
        }
        ?>
        <?php
        if (($noNativeLogin === false) && !empty($_SESSION['user']->id)) {
            ?>
            <div>
                <center>
                    <button class="settings"
                            onclick="document.location.href='user'">
                        <i class="fa" aria-hidden="true"></i> <?php echo i8ln('Activate Key'); ?>
                    </button>
                </center>
            </div>
            <div>
                <center>
                    <button class="settings"
                            onclick="document.location.href='logout.php'">
                        <i class="fa" aria-hidden="true"></i> <?php echo i8ln('Logout'); ?>
                    </button>
                </center>
            </div><br>
            <div><center><p>
            <?php
            $time = date("Y-m-d", $_SESSION['user']->expire_timestamp);
            
            echo $_SESSION['user']->user . "<br>";
            if ($_SESSION['user']->expire_timestamp < time()) {
                echo "<span style='color: green;'>" . i8ln('Membership expires on') . " {$time}</span>";
            } else {
                echo "<span style='color: red;'>" . i8ln('Membership expired on') . " {$time}</span>";
            } ?>
            </p></center></div>
        <?php
        }
        ?>
    </nav>
    <nav id="stats">
        <div class="switch-container">
            <?php
            if ( $worldopoleUrl !== "" ) {
                ?>
                <div class="switch-container">
                    <div>
                        <center><a href="<?= $worldopoleUrl ?>">Full Stats</a></center>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="switch-container">
                <center><h1 id="stats-ldg-label"><?php echo i8ln( 'Loading' ) ?>...</h1></center>
            </div>
            <div class="stats-label-container">
                <center><h1 id="stats-pkmn-label"></h1></center>
            </div>
            <div id="pokemonList" style="color: black;">
                <table id="pokemonList_table" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Anzahl</th>
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
        <center><h1><?php echo i8ln( 'Loading' ) ?>...</h1></center>
    </nav>

    <div id="motd" title=""></div>

    <div id="map"></div>
    <div class="global-raid-modal">

    </div>
    <?php if ( ! $noManualNests ) { ?>
        <div class="global-nest-modal" style="display:none;">
            <input type="hidden" name="pokemonID" class="pokemonID"/>
            <?php pokemonFilterImages( $noPokemonNumbers, 'pokemonSubmitFilter(event)', $excludeNestMons, 5 ); ?>
            <div class="button-container">
                <button type="button" onclick="manualNestData(event);" class="submitting-nests"><i
                        class="fa fa-binoculars"
                        style="margin-right:10px;"></i><?php echo i8ln( 'Submit Nest' ); ?>
                </button>
            </div>
        </div>
    <?php } ?>
    <?php if ( ! $noRenamePokestops ) { ?>
        <div class="rename-modal" style="display: none;">
	   <input type="text" id="pokestop-name" name="pokestop-name"
		  placeholder="<?php echo i8ln( 'Enter New Pokéstop Name' ); ?>" data-type="pokestop"
                  class="search-input">
             <div class="button-container">
                <button type="button" onclick="renamePokestopData(event);" class="renamepokestopid"><i
                        class="fa fa-edit"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'Rename Pokestop' ); ?>
                </button>
            </div>
        </div>
    <?php } ?>
    <?php if ( ! $noConvertPokestops ) { ?>
        <div class="convert-modal" style="display: none;">
             <div class="button-container">
                <button type="button" onclick="convertPokestopData(event);" class="convertpokestopid"><i
                        class="fa fa-refresh"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'Convert to gym' ); ?>
                </button>
            </div>
        </div>
    <?php } ?>
    <?php if ( ! $noEditCommunity ) { ?>
        <div class="editcommunity-modal" style="display: none;">
	   <input type="text" id="community-name" name="community-name"
		  placeholder="<?php echo i8ln( 'Enter New community Name' ); ?>" data-type="community-name"
		  class="search-input">
	   <input type="text" id="community-description" name="community-description"
		  placeholder="<?php echo i8ln( 'Enter New community Description' ); ?>" data-type="community-description"
		  class="search-input">
	   <input type="text" id="community-invite" name="community-invite"
		  placeholder="<?php echo i8ln( 'Enter New community Invite link' ); ?>" data-type="community-invite"
		  class="search-input">
	     <div class="button-container">
                <button type="button" onclick="editCommunityData(event);" class="editcommunityid"><i
                        class="fa fa-edit"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'Save Changes' ); ?>
                </button>
            </div>
        </div>
    <?php } ?>
    <?php if ( ! $noPortals ) { ?>
        <div class="convert-portal-modal" style="display: none;">
             <div class="button-container">
                <button type="button" onclick="convertPortalToPokestopData(event);" class="convertportalid"><i
                        class="fa fa-refresh"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'Convert to pokestop' ); ?>
		</button>
                <button type="button" onclick="convertPortalToGymData(event);" class="convertportalid"><i
                        class="fa fa-refresh"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'Convert to gym' ); ?>
		</button>
                <button type="button" onclick="markPortalChecked(event);" class="convertportalid"><i
                        class="fa fa-times"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'No Pokestop or Gym' ); ?>
		</button>
            </div>
        </div>
    <?php } ?>
    <?php if ( ! $noPoi ) { ?>
        <div class="mark-poi-modal" style="display: none;">
             <div class="button-container">
                <button type="button" onclick="markPoiSubmitted(event);" class="markpoiid"><i
                        class="fa fa-refresh"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'Mark as submitted' ); ?>
		</button>
                <button type="button" onclick="markPoiDeclined(event);" class="markpoiid"><i
                        class="fa fa-times"
                        style="margin-right:10px; vertical-align: middle; font-size: 1.5em;"></i><?php echo i8ln( 'Mark as declined' ); ?>
		</button>
            </div>
        </div>
    <?php } ?>
    <?php if ( ! $noDiscordLogin ) { ?>
        <div class="accessdenied-modal" style="display: none;">
            <?php if ( $copyrightSafe === false ) { ?>
                <img src="static/images/accessdenied.png" alt="PikaSquad" width="250">
            <?php } ?>
            <center>Zugriff verweigert</center>
            <br>
            <?php echo 'Du bist entweder noch kein Mitglied unseres Discord-Servers oder du bist auf einem Server, der auf unserer Blacklist steht. Klicke <a href="' .$discordUrl .'">hier</a> um unserem Server beizutreten!'; ?>
        </div>
    <?php } ?>
    <?php if ( ! $noManualQuests ) { ?>
        <div class="quest-modal" style="display: none;">
            <input type="hidden" value="" name="questPokestop" class="questPokestop"/>
            <?php
            $json   = file_get_contents( 'static/dist/data/questtype.min.json' );
            $questtypes  = json_decode( $json, true );

            $json    = file_get_contents( 'static/dist/data/rewardtype.min.json' );
            $rewardtypes   = json_decode( $json, true );

            $json    = file_get_contents( 'static/dist/data/conditiontype.min.json' );
            $conditiontypes   = json_decode( $json, true );

	    $json    = file_get_contents( 'static/dist/data/pokemon.min.json' );
	    $encounters = json_decode( $json, true );

	    $json    = file_get_contents( 'static/dist/data/items.min.json' );
	    $items = json_decode( $json, true );
            ?>
            <label for="questTypeList"><?php echo i8ln( 'Quest' ); ?>
            <select id="questTypeList" name="questTypeList" class="questTypeList">
                <option />
                <?php
                foreach ( $questtypes as $key => $value ) {
                    if ( ! in_array( $key, $hideQuestTypes ) ) {
                    ?>
                        <option value="<?php echo $key; ?>"><?php echo i8ln( $value['text'] ); ?></option>
                    <?php
                    }
                }
                ?>
	    </select>
            <select id="questAmountList" name="questAmountList" class="questAmountList">
                <option />
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
	    </select>
            </label>
            <label for="conditionTypeList"><?php echo i8ln( 'Conditions' ); ?>
            <select id="conditionTypeList" name="conditionTypeList" class="conditionTypeList">
                <option />
                <?php
                foreach ( $conditiontypes as $key => $value ) {
                    if ( ! in_array( $key, $hideConditionTypes ) ) {
                    ?>
                        <option value="<?php echo $key; ?>"><?php echo i8ln( $value['text'] ); ?></option>
                    <?php
                    }
                }
                ?>
	    </select>
            <select id="pokeCatchList" name="pokeCatchList" class="pokeCatchList" multiple></select>
	    <select id="typeCatchList" name="typeCatchList" class="typeCatchList" multiple>
                <option value="1"><?php echo i8ln( 'Normal' ); ?></option>
                <option value="2"><?php echo i8ln( 'Fighting' ); ?></option>
                <option value="3"><?php echo i8ln( 'Flying' ); ?></option>
                <option value="4"><?php echo i8ln( 'Poison' ); ?></option>
                <option value="5"><?php echo i8ln( 'Ground' ); ?></option>
                <option value="6"><?php echo i8ln( 'Rock' ); ?></option>
                <option value="7"><?php echo i8ln( 'Bug' ); ?></option>
                <option value="8"><?php echo i8ln( 'Ghost' ); ?></option>
                <option value="9"><?php echo i8ln( 'Steel' ); ?></option>
                <option value="10"><?php echo i8ln( 'Fire' ); ?></option>
                <option value="11"><?php echo i8ln( 'Water' ); ?></option>
                <option value="12"><?php echo i8ln( 'Grass' ); ?></option>
                <option value="13"><?php echo i8ln( 'Electric' ); ?></option>
                <option value="14"><?php echo i8ln( 'Psychic' ); ?></option>
                <option value="15"><?php echo i8ln( 'Ice' ); ?></option>
                <option value="16"><?php echo i8ln( 'Dragon' ); ?></option>
                <option value="17"><?php echo i8ln( 'Dark' ); ?></option>
                <option value="18"><?php echo i8ln( 'Fairy' ); ?></option>
            </select>
            <select id="raidLevelList" name="raidLevelList" class="raidLevelList">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
	    </select>
	    <select id="throwTypeList" name="throwTypeList" class="throwTypeList">
		<option />
                <option value="10"><?php echo i8ln( 'Nice' ); ?></option>
                <option value="11"><?php echo i8ln( 'Great' ); ?></option>
                <option value="12"><?php echo i8ln( 'Excellent' ); ?></option>
            </select>
            <select id="curveThrow" class="curveThrow" class="curveThrow">
		<option />
                <option value="0"><?php echo i8ln( 'Without curve throw' ); ?></option>
                <option value="1"><?php echo i8ln( 'With curve throw' ); ?></option>
            </select>
            </label>
            <label for="rewardTypeList"><?php echo i8ln( 'Reward' ); ?>
            <select id="rewardTypeList" name="rewardTypeList" class="rewardTypeList">
                <option />
                <?php
                foreach ( $rewardtypes as $key => $value ) {
                    if ( ! in_array( $key, $hideRewardTypes ) ) {
                    ?>
                        <option value="<?php echo $key; ?>"><?php echo i8ln( $value['text'] ); ?></option>
                    <?php
                    }
                }
                ?>
	    </select>
            <select id="pokeQuestList" name="pokeQuestList" class="pokeQuestList">
                <option />
                <?php
                foreach ( $encounters as $key => $value ) {
                    if ( in_array( $key, $showEncounters ) ) {
                    ?>
                        <option value="<?php echo $key; ?>"><?php echo i8ln( $value['name'] ); ?></option>
                    <?php
                    }
                }
                ?>
	    </select>
            <select id="itemQuestList" name="itemQuestList" class="itemQuestList">
                <option />
                <?php
                foreach ( $items as $key => $value ) {
                    if ( in_array( $key, $showItems ) ) {
                    ?>
                        <option value="<?php echo $key; ?>"><?php echo i8ln( $value['name'] ); ?></option>
                    <?php
                    }
                }
                ?>
	    </select>
            <select id="itemAmountList" name="itemAmountList" class="itemAmountList">
                <option />
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
            <select id="dustQuestList" name="dustQuestList" class="dustQuestList">
                <option />
                <option value="200">200</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
	    </select>
            </label>
            <div class="button-container">
                <button type="button" onclick="manualQuestData(event);" class="submitting-quest"><i
                        class="fa fa-binoculars"
                        style="margin-right:10px;"></i><?php echo i8ln( 'Submit Quest' ); ?>
                </button>
            </div>
        </div>
    <?php } ?>
    <div class="fullscreen-toggle">
        <button class="map-toggle-button" onClick="toggleFullscreenMap();"><i class="fa fa-expand" aria-hidden="true"></i></button>
    </div>
    <?php if ( ( ! $noGyms || ! $noPokestops ) && ! $noSearch ) { ?>
        <div class="search-container">
            <button class="search-modal-button" onClick="openSearchModal(event);"><i class="fa fa-search"
                                                                                     aria-hidden="true"></i></button>
            <div class="search-modal" style="display:none;">
                <div id="search-tabs">
                    <ul>
                        <?php if ( ! $noQuests && ! $noSearchManualQuests ) { ?>
                            <li><a href="#tab-rewards"><img src="static/images/reward.png"/></a></li>
                        <?php }
                        if ( ! $noSearchNests ) { ?>
                            <li><a href="#tab-nests"><img src="static/images/nest.png"/></a></li>
                        <?php }
                        if ( ! $noSearchGyms ) { ?>
                            <li><a href="#tab-gym"><img src="static/forts/ingame/Uncontested.png"/></a></li>
                        <?php }
                        if ( ! $noSearchPokestops ) { ?>
                            <li><a href="#tab-pokestop"><img src="static/forts/Pstop-large.png"/></a></li>
                        <?php }
                        if ( ! $noSearchPortals ) { ?>
                            <li><a href="#tab-portals"><img src="static/images/portal.png"/></a></li>
			<?php } ?>
                    </ul>
                    <?php if ( ! $noQuests && ! $noSearchManualQuests ) { ?>
                        <div id="tab-rewards">
                            <input type="search" id="reward-search" name="reward-search"
                                   placeholder="<?php echo i8ln( 'Enter Reward Name' ); ?>"
                                   data-type="reward" class="search-input"/>
                            <ul id="reward-search-results" class="search-results reward-results"></ul>
                        </div>
                    <?php } ?>
                    <?php if ( ! $noSearchNests ) { ?>
                        <div id="tab-nests">
                            <input type="search" id="nest-search" name="nest-search"
                                   placeholder="<?php echo i8ln( 'Enter Pokemon or Type' ); ?>"
                                   data-type="nests" class="search-input"/>
                            <ul id="nest-search-results" class="search-results nest-results"></ul>
                        </div>
                    <?php } ?>
                    <?php if ( ! $noSearchGyms ) { ?>
                        <div id="tab-gym">
                            <input type="search" id="gym-search" name="gym-search"
                                   placeholder="<?php echo i8ln( 'Enter Gym Name' ); ?>"
                                   data-type="forts" class="search-input"/>
                            <ul id="gym-search-results" class="search-results gym-results"></ul>
                        </div>
		    <?php } ?>
		    <?php if ( ! $noSearchPokestops ) { ?>
                        <div id="tab-pokestop">
                            <input type="search" id="pokestop-search" name="pokestop-search"
                                   placeholder="<?php echo i8ln( 'Enter Pokestop Name' ); ?>" data-type="pokestops"
                                   class="search-input"/>
                            <ul id="pokestop-search-results" class="search-results pokestop-results"></ul>
                        </div>
		    <?php } ?>
		    <?php if ( ! $noSearchPortals ) { ?>
                        <div id="tab-portals">
                            <input type="search" id="portals-search" name="portals-search"
                                   placeholder="<?php echo i8ln( 'Enter Portal Name' ); ?>" data-type="portals"
                                   class="search-input"/>
                            <ul id="portals-search-results" class="search-results portals-results"></ul>
                        </div>
		    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php
    if ( ( ! $noPokemon && ! $noManualPokemon ) || ( ! $noGyms && ! $noManualGyms ) || ( ! $noPokestops && ! $noManualPokestops ) || ( ! $noAddNewNests && ! $noNests ) || ( !$noAddNewCommunity && ! $noCommunity ) || ( !$noAddPoi && ! $noPoi ) ) {
        ?>
        <button class="submit-on-off-button" onclick="$('.submit-on-off-button').toggleClass('on');">
            <i class="fa fa-map-marker submit-to-map" aria-hidden="true"></i>
        </button>
        <div class="submit-modal" style="display:none;">
            <input type="hidden" value="" name="submitLatitude" class="submitLatitude"/>
            <input type="hidden" value="" name="submitLongitude" class="submitLongitude"/>
            <div id="submit-tabs">
                <ul>
                    <?php if ( ! $noManualPokemon && !$noPokemon ) {
                        ?>
                        <li><a href="#tab-pokemon"><img src="static/images/pokeball.png"/></a></li>
                    <?php } ?>
                    <?php if ( ! $noManualGyms && !$noGyms ) {
                        ?>
                        <li><a href="#tab-gym"><img src="static/forts/ingame/Uncontested.png"/></a></li>
                    <?php } ?>
                    <?php if ( ! $noManualPokestops && !$noPokestops) {
                        ?>
                        <li><a href="#tab-pokestop"><img src="static/forts/Pstop-large.png"/></a></li>
                    <?php } ?>
                    <?php if ( ! $noAddNewNests && !$noNests ) {
                        ?>
                        <li><a href="#tab-nests"><img src="static/images/nest.png"/></a></li>
		    <?php } ?>
                    <?php if ( ! $noAddNewCommunity && !$noCommunity ) {
                        ?>
                        <li><a href="#tab-communities"><img src="static/images/community.png"/></a></li>
                    <?php } ?>
                    <?php if ( ! $noAddPoi && !$noPoi ) {
                        ?>
                        <li><a href="#tab-poi"><img src="static/images/playground.png"/></a></li>
                    <?php } ?>
                </ul>
                <?php if ( ! $noManualPokemon && !$noPokemon  ) {
                    ?>
                    <div id="tab-pokemon">
                        <input type="hidden" name="pokemonID" class="pokemonID"/>
                        <?php pokemonFilterImages( $noPokemonNumbers, 'pokemonSubmitFilter(event)', $pokemonToExclude, 6 ); ?>
                        <div class="button-container">
                            <button type="button" onclick="manualPokemonData(event);" class="submitting-pokemon"><i
                                    class="fa fa-binoculars"
                                    style="margin-right:10px;"></i><?php echo i8ln( 'Submit Pokemon' ); ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( ! $noManualGyms && !$noGyms ) {
                    ?>
                    <div id="tab-gym">
                        <input type="text" id="gym-name" name="gym-name"
                               placeholder="<?php echo i8ln( 'Enter Gym Name' ); ?>" data-type="forts"
                               class="search-input">
                        <div class="button-container">
                            <button type="button" onclick="manualGymData(event);" class="submitting-gym"><i
                                    class="fa fa-binoculars"
                                    style="margin-right:10px;"></i><?php echo i8ln( 'Submit Gym' ); ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( ! $noManualPokestops && !$noPokestops ) {
                    ?>
                    <div id="tab-pokestop">
                        <input type="text" id="pokestop-name" name="pokestop-name"
                               placeholder="<?php echo i8ln( 'Enter Pokestop Name' ); ?>" data-type="pokestop"
                               class="search-input">
                        <div class="button-container">
                            <button type="button" onclick="manualPokestopData(event);" class="submitting-pokestop"><i
                                    class="fa fa-binoculars"
                                    style="margin-right:10px;"></i><?php echo i8ln( 'Submit Pokestop' ); ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( ! $noAddNewNests && !$noNests ) {
                    ?>
                    <div id="tab-nests">
                        <input type="hidden" name="pokemonID" class="pokemonID"/>
                        <?php pokemonFilterImages( $noPokemonNumbers, 'pokemonSubmitFilter(event)', $excludeNestMons, 7 ); ?>
                        <div class="button-container">
                            <button type="button" onclick="submitNewNest(event);" class="submitting-nest"><i
                                    class="fa fa-binoculars"
                                    style="margin-right:10px;"></i><?php echo i8ln( 'Submit Nest' ); ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( ! $noAddNewCommunity && !$noCommunity ) {
                    ?>
                    <div id="tab-communities">
                        <input type="text" name="community-name" class="community-name"
                               placeholder="<?php echo i8ln( 'Enter Community Name' ); ?>" data-type="name"
			       class="search-input">
                        <input type="text" name="community-description" class="community-description"
                               placeholder="<?php echo i8ln( 'Enter description' ); ?>" data-type="description"
			       class="search-input">
                        <input type="text" name="community-invite" class="community-invite"
                               placeholder="<?php echo i8ln( 'Whatsapp, Telegram, Discord Link' ); ?>" data-type="invite-link"
			       class="search-input">
			<h6><center><?php echo i8ln( 'Link must be valid and start with https://' ); ?></center></h6>
                        <div class="button-container">
                            <button type="button" onclick="submitNewCommunity(event);" class="submitting-community"><i
                                    class="fa fa-comments"
                                    style="margin-right:10px;"></i><?php echo i8ln( 'Submit Community' ); ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( ! $noAddPoi && !$noPoi ) {
                    ?>
                    <div id="tab-poi">
                        <input type="text" name="poi-name" class="poi-name"
                               placeholder="<?php echo i8ln( 'Enter POI Name' ); ?>" data-type="name"
			       class="search-input">
                        <input type="text" name="poi-description" class="poi-description"
                               placeholder="<?php echo i8ln( 'Enter description' ); ?>" data-type="description"
			       class="search-input">
                        <div class="button-container">
			<h6><center><?php echo i8ln( 'If you submit a POI you agree that your discord username will be shown in the marker label' ); ?></center></h6>
                            <button type="button" onclick="submitPoi(event);" class="submitting-poi"><i
                                    class="fa fa-comments"
                                    style="margin-right:10px;"></i><?php echo i8ln( 'Submit POI' ); ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.9.1/polyfill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/skel/3.0.1/skel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/select2.full.min.js"></script>
<script src="node_modules/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="node_modules/moment/min/moment-with-locales.min.js"></script>
<script src="https://code.createjs.com/soundjs-0.6.2.min.js"></script>
<script src="node_modules/push.js/bin/push.min.js"></script>
<script src="node_modules/long/src/long.js"></script>
<script src="node_modules/leaflet/dist/leaflet.js"></script>
<script src="node_modules/leaflet-geosearch/dist/bundle.min.js"></script>
<script src="static/js/vendor/s2geometry.js"></script>
<script src="static/dist/js/app.min.js"></script>
<script src="static/js/vendor/classie.js"></script>
<script src="node_modules/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
<script src='static/js/vendor/Leaflet.fullscreen.min.js'></script>
<script src="static/js/vendor/smoothmarkerbouncing.js"></script>
<script src='https://maps.googleapis.com/maps/api/js?key=<?php $gmapsKey ?> ' async defer></script>
<script src="static/js/vendor/Leaflet.GoogleMutant.js"></script>
<script src="static/js/vendor/turf.min.js"></script>
<script>
    var centerLat = <?= $startingLat; ?>;
    var centerLng = <?= $startingLng; ?>;
    var locationSet = <?= $locationSet; ?>;
    var motd = <?php echo $noMotd ? 'false' : 'true' ?>;
    var zoom<?php echo $zoom ? " = " . $zoom : null; ?>;
    var encounterId<?php echo $encounterId ? " = '" . $encounterId . "'" : null; ?>;
    var maxZoom = <?= $maxZoomIn; ?>;
    var minZoom = <?= $maxZoomOut; ?>;
    var maxLatLng = <?= $maxLatLng; ?>;
    var disableClusteringAtZoom = <?= $disableClusteringAtZoom; ?>;
    var zoomToBoundsOnClick = <?= $zoomToBoundsOnClick; ?>;
    var maxClusterRadius = <?= $maxClusterRadius; ?>;
    var spiderfyOnMaxZoom = <?= $spiderfyOnMaxZoom; ?>;
    var osmTileServer = '<?php echo $osmTileServer; ?>';
    var mapStyle = '<?php echo $mapStyle ?>';
    var gmapsKey = '<?php echo $gmapsKey ?>';
    var hidePokemon = <?php echo $noHidePokemon ? '[]' : $hidePokemon ?>;
    var excludeMinIV = <?php echo $noExcludeMinIV ? '[]' : $excludeMinIV ?>;
    var minIV = <?php echo $noMinIV ? '""' : $minIV ?>;
    var minLevel = <?php echo $noMinLevel ? '""' : $minLevel ?>;
    var notifyPokemon = <?php echo $noNotifyPokemon ? '[]' : $notifyPokemon ?>;
    var notifyRarity = <?php echo $noNotifyRarity ? '[]' : $notifyRarity ?>;
    var notifyIv = <?php echo $noNotifyIv ? '""' : $notifyIv ?>;
    var notifyLevel = <?php echo $noNotifyLevel ? '""' : $notifyLevel ?>;
    var notifyRaid = <?php echo $noNotifyRaid ? 0 : $notifyRaid ?>;
    var notifyBounce = <?php echo $notifyBounce ?>;
    var notifyNotification = <?php echo $notifyNotification ?>;
    var enableRaids = <?php echo $noRaids ? 'false' : $enableRaids ?>;
    var activeRaids = <?php echo $activeRaids ?>;
    var minRaidLevel = <?php echo $minRaidLevel ?>;
    var maxRaidLevel = <?php echo $maxRaidLevel ?>;
    var enableGyms = <?php echo $noGyms ? 'false' : $enableGyms ?>;
    var enableNests = <?php echo $noNests ? 'false' : $enableNests ?>;
    var enableCommunities = <?php echo $noCommunity ? 'false' : $enableCommunities ?>;
    var gymSidebar = <?php echo $noGymSidebar ? 'false' : $gymSidebar ?>;
    var enablePokemon = <?php echo $noPokemon ? 'false' : $enablePokemon ?>;
    var enablePokestops = <?php echo $noPokestops ? 'false' : $enablePokestops ?>;
    var enableLured = <?php echo $noLures ? 'false' : $enableLured ?>;
    var noQuests = <?php echo $noQuests === true ? 'true' : 'false' ?>;
    var enableQuests = <?php echo $noQuests ? 'false' : $enableQuests ?>;
    var hideQuestsPokemon = <?php echo $hideQuestsPokemon ? '[]' : $hideQuestsPokemon ?>;
    var hideQuestsItem = <?php echo $hideQuestsItem ? '[]' : $hideQuestsItem ?>;
    var enableNewPortals = <?php echo ( ( $map != "monocle" ) || ( $fork == "alternate" ) ) ? $enableNewPortals : 0 ?>;
    var enableWeatherOverlay = <?php echo ! $noWeatherOverlay ? $enableWeatherOverlay : 'false' ?>;
    var enableScannedLocations = <?php echo $map != "monocle" && ! $noScannedLocations ? $enableScannedLocations : 'false' ?>;
    var enableSpawnpoints = <?php echo $noSpawnPoints ? 'false' : $enableSpawnPoints ?>;
    var enableRanges = <?php echo $noRanges ? 'false' : $enableRanges ?>;
    var enableScanPolygon = <?php echo $noScanPolygon ? 'false' : $enableScanPolygon ?>;
    var enableScanPolygonQuest = <?php echo $noScanPolygonQuest ? 'false' : $enableScanPolygonQuest ?>;
    var enableScanPolygonPvp = <?php echo $noScanPolygonPvp ? 'false' : $enableScanPolygonPvp ?>;
    var geoJSONfile = '<?php echo $noScanPolygon ? '' : $geoJSONfile ?>';
    var nestJSONfile = '<?php echo $noNests ? '' : $nestJSONfile ?>';
    var geoJSONfileQuest = '<?php echo $noScanPolygonQuest ? '' : $geoJSONfileQuest ?>';
    var geoJSONfilePvp = '<?php echo $noScanPolygonPvp ? '' : $geoJSONfilePvp ?>';
    var pvptext1 = '<?php echo $noScanPolygonPvp ? '' : $pvptext1 ?>';
    var pvptext2 = '<?php echo $noScanPolygonPvp ? '' : $pvptext2 ?>';
    var pvptext3 = '<?php echo $noScanPolygonPvp ? '' : $pvptext3 ?>';
    var pvptext4 = '<?php echo $noScanPolygonPvp ? '' : $pvptext4 ?>';
	var verifiedDespawnTimer = '<?php echo $verifiedDespawnTimer ?>';
    var notifySound = <?php echo $noNotifySound ? 'false' : $notifySound ?>;
    var criesSound = <?php echo $noCriesSound ? 'false' : $criesSound ?>;
    var enableStartMe = <?php echo $noStartMe ? 'false' : $enableStartMe ?>;
    var enableStartLast = <?php echo ( ! $noStartLast && $enableStartMe === 'false' ) ? $enableStartLast : 'false' ?>;
    var enableFollowMe = <?php echo $noFollowMe ? 'false' : $enableFollowMe ?>;
    var enableSpawnArea = <?php echo $noSpawnArea ? 'false' : $enableSpawnArea ?>;
    var iconSize = <?php echo $iconSize ?>;
    var iconNotifySizeModifier = <?php echo $iconNotifySizeModifier ?>;
    var locationStyle = '<?php echo $locationStyle ?>';
    var gymStyle = '<?php echo $gymStyle ?>';
    var spriteFileLarge = '<?php echo $copyrightSafe ? 'static/icons-safe-1-bigger.png' : 'static/icons-im-1-bigger.png' ?>';
    var weatherSpritesSrc = '<?php echo $copyrightSafe ? 'static/sprites-safe/' : 'static/sprites-pokemon/' ?>';
    var icons = '<?php echo $copyrightSafe ? 'static/icons-safe/' : $iconRepository ?>';
    var weatherColors = <?php echo json_encode( $weatherColors ); ?>;
    var mapType = '<?php echo $map; ?>';
    var triggerGyms = <?php echo $triggerGyms ?>;
    var noExGyms = <?php echo $noExGyms === true ? 'true' : 'false' ?>;
    var noParkInfo = <?php echo $noParkInfo === true ? 'true' : 'false' ?>;
    var onlyTriggerGyms = <?php echo $onlyTriggerGyms === true ? 'true' : 'false' ?>;
    var showBigKarp = <?php echo $noBigKarp === true ? 'true' : 'false' ?>;
	var enableBigKarps = <?php echo $noBigKarp ? 'false' : $enableBigKarps ?>;
    var showTinyRat = <?php echo $noTinyRat === true ? 'true' : 'false' ?>;
	var enableTinyRats = <?php echo $noTinyRat ? 'false' : $enableTinyRats ?>;
    var hidePokemonCoords = <?php echo $hidePokemonCoords === true ? 'true' : 'false' ?>;
    var directionProvider = '<?php echo $noDirectionProvider === true ? $directionProvider : 'google' ?>';
    var exEligible = <?php echo $noExEligible === true ? 'false' : $exEligible  ?>;
    var raidBossActive = <?php echo json_encode( $raidBosses ); ?>;
    var manualRaids = <?php echo $noManualRaids === true ? 'false' : 'true' ?>;
    var pokemonReportTime = <?php echo $pokemonReportTime === true ? 'true' : 'false' ?>;
    var noDeleteGyms = <?php echo $noDeleteGyms === true ? 'true' : 'false' ?>;
    var noToggleExGyms = <?php echo $noToggleExGyms === true ? 'true' : 'false' ?>;
    var defaultUnit = '<?php echo $defaultUnit ?>';
    var noDeletePokestops = <?php echo $noDeletePokestops === true ? 'true' : 'false' ?>;
    var noDeleteNests = <?php echo $noDeleteNests === true ? 'true' : 'false' ?>;
    var noManualNests = <?php echo $noManualNests === true ? 'true' : 'false' ?>;
    var noManualQuests = <?php echo $noManualQuests === true ? 'true' : 'false' ?>;
    var noAddNewCommunity = <?php echo $noAddNewCommunity === true ? 'true' : 'false' ?>;
    var noDeleteCommunity = <?php echo $noDeleteCommunity === true ? 'true' : 'false' ?>;
    var noEditCommunity = <?php echo $noEditCommunity === true ? 'true' : 'false' ?>;
    var login = <?php echo $noNativeLogin === false || $noDiscordLogin === false  ? 'true' : 'false' ?>;
    var expireTimestamp = <?php echo isset($_SESSION['user']->expire_timestamp) ? $_SESSION['user']->expire_timestamp : 0 ?>;
    var timestamp = <?php echo time() ?>;
    var noRenamePokestops = <?php echo $noRenamePokestops === true ? 'true' : 'false' ?>;
    var noConvertPokestops = <?php echo $noConvertPokestops === true ? 'true' : 'false' ?>;
    var noWhatsappLink = <?php echo $noWhatsappLink === true ? 'true' : 'false' ?>;
    var noWhatsappLinkQuests = <?php echo $noWhatsappLinkQuests === true ? 'true' : 'false' ?>;
    var enablePoi = <?php echo $noPoi ? 'false' : $enablePoi ?>;
    var enablePortals = <?php echo $noPortals ? 'false' : $enablePortals ?>;
    var noDeletePoi = <?php echo $noDeletePoi === true ? 'true' : 'false' ?>;
    var noMarkPoi = <?php echo $noMarkPoi === true ? 'true' : 'false' ?>;
    var noPortals = <?php echo $noPortals === true ? 'true' : 'false' ?>;
    var enableS2Cells = <?php echo $noS2Cells ? 'false' : $enableS2Cells ?>;
    var enableLevel13Cells = <?php echo $noS2Cells ? 'false' : $enableLevel13Cells ?>;
    var enableLevel14Cells = <?php echo $noS2Cells ? 'false' : $enableLevel14Cells ?>;
    var enableLevel17Cells = <?php echo $noS2Cells ? 'false' : $enableLevel17Cells ?>;
    var noDeletePortal = <?php echo $noDeletePortal === true ? 'true' : 'false' ?>;
    var noConvertPortal = <?php echo $noConvertPortal === true ? 'true' : 'false' ?>;
    var markPortalsAsNew = <?php echo $markPortalsAsNew ?>;
    var copyrightSafe = <?php echo $copyrightSafe === true ? 'true' : 'false' ?>;
    var noRarityDisplay = <?php echo $noRarityDisplay === true ? 'true' : 'false' ?>;
    var noWeatherIcons = <?php echo $noWeatherIcons === true ? 'true' : 'false' ?>;
    var noWeatherShadow = <?php echo $noWeatherShadow === true ? 'true' : 'false' ?>;
    var noGymScannedText = <?php echo $noGymScannedText === true ? 'true' : 'false' ?>;
    var noMaplink = <?php echo $noMaplink === true ? 'true' : 'false' ?>;
    var noGymTeamInfos = <?php echo $noGymTeamInfos === true ? 'true' : 'false' ?>;
    var noOutdatedGyms = <?php echo $noOutdatedGyms === true ? 'true' : 'false' ?>;
    var $noExportImport = <?php echo $noOutdatedGyms === true ? 'true' : 'false' ?>;
    var noBattleStatus = <?php echo $noBattleStatus === true ? 'true' : 'false' ?>;
    var battleStatus = <?php echo $noBattleStatus === true ? 'false' : $battleStatus  ?>;
	
	
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="static/dist/js/map.common.min.js"></script>
<script src="static/dist/js/map.min.js"></script>
<script src="static/dist/js/stats.min.js"></script>
<script>
$( document ).ready(function() {
    initMap()
})
</script>
</body>
</html>
