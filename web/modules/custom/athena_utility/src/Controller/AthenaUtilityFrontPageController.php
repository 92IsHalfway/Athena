<?php

namespace Drupal\athena_utility\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\athena_utility\AthenaUtility;
use Drupal\athena_utility\AthenaLeagueUtility;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AthenaUtilityFrontPageController.
 *
 * @package Drupal\athena_utility\Controller
 */
class AthenaUtilityFrontPageController extends ControllerBase {

  /**
   * An instance of the Athena Utility.
   *
   * @var \Drupal\athena_utility\AthenaUtility
   */
  protected $athenaUtility;

  /**
   * An instance of the Athena League Utility.
   *
   * @var \Drupal\athena_utility\AthenaLeagueUtility
   */
  protected $leagueUtility;

  /**
   * AthenaUtilityFrontPageController constructor.
   *
   * @param \Drupal\athena_utility\AthenaUtility $athenaUtility
   *   An instance of the Athena Utility.
   * @param \Drupal\athena_utility\AthenaLeagueUtility $leagueUtility
   *   An instance of the Athena League Utility.
   */
  public function __construct(AthenaUtility $athenaUtility, AthenaLeagueUtility $leagueUtility) {
    $this->athenaUtility = $athenaUtility;
    $this->leagueUtility = $leagueUtility;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('athena_utility.utility'),
      $container->get('athena_utility.league_utility')
    );
  }

  /**
   * The front page dashboard for a user.
   *
   * @return array
   *   The render array.
   */
  public function front() {
    $puuid = $this->leagueUtility->getPuuid($this->athenaUtility->getCurrentUser()->id());
    $dashboard_cache_key = 'athena_utility_dashboard_' . $puuid;
    $cache_tags = $this->leagueUtility->getCacheTagsToInvalidate($this->athenaUtility->getCurrentUser()->id());

    if ($puuid == NULL) {
      $dashboard_cache_key = 'athena_utility_dashboard_null';
    }

    $build = [
      '#pre_render' => [[$this, 'frontPreRender']],
      '#cache' => [
        'keys' => [
          $dashboard_cache_key,
        ],
        'tags' => $cache_tags,
        'contexts' => [
          'user',
        ],
      ],
      '#puuid' => $puuid,
    ];

    return $build;
  }

  /**
   * Front page pre-render.
   *
   * @param array $build
   *   The render array.
   *
   * @return array
   *   The render array.
   */
  public function frontPreRender(array $build) {
    $puuid = $build['#puuid'];
    $stats_data = [];
    $summoner_data = [];
    $static_data = [];

    $cache_default = $this->athenaUtility->getCache();

    if ($puuid != NULL) {
      $stats = $cache_default->get('athena_utility_league_stats_' . $puuid);
      $summoner = $cache_default->get('athena_utility_league_summoner_' . $puuid);
    }
    else {
      $stats = [];
      $summoner = [];
    }

    $static = $cache_default->get('athena_utility_league_static');

    if (!empty($stats)) {
      $stats_data = $stats->data;
    }

    if (!empty($summoner)) {
      $summoner_data = $summoner->data;
    }

    if (!empty($static)) {
      $static_data = $static->data;
    }

    $build['dashboard'] = [
      '#theme' => 'athena_utility_league_dashboard',
      '#attached' => [
        'library' => [
          'athena_utility/league_dashboard',
        ],
        'drupalSettings' => [
          'stats_data' => $stats_data,
          'summoner_data' => $summoner_data,
          'static_data' => $static_data,
        ],
      ],
      '#stats_data' => $stats_data,
      '#summoner_data' => $summoner_data,
      '#static_data' => $static_data,
    ];

    return $build;
  }

}
