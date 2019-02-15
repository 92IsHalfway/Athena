<?php

namespace Drupal\athena_utility;

use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;
use Drupal\Core\Entity\EntityStorageException;

/**
 * Class AthenaLeagueUtility.
 *
 * @package Drupal\athena_utility
 */
class AthenaLeagueUtility {

  /**
   * The base api url.
   */
  const API_WEBSITE = 'api.riotgames.com/lol';

  /**
   * Gets a summoner's information by account name.
   */
  const SUMMONER_BY_NAME = '/summoner/v4/summoners/by-name/{name}';

  /**
   * Gets a summoner's match history (100 DESC) by account ID.
   */
  const MATCHES_BY_ACCOUNT_ID = '/match/v4/matchlists/by-account/{account_id}';

  /**
   * Gets match information for a given match ID.
   */
  const MATCH_BY_MATCH_ID = '/match/v4/matches/{match_id}';

  /**
   * Gets a summoner's information by PUUID.
   */
  const SUMMONER_BY_PUUID = '/summoner/v4/summoners/by-puuid/{puuid}';

  const DATA_DRAGON_URL = 'https://ddragon.leagueoflegends.com';

  /**
   * An instance of the Athena Utility.
   *
   * @var \Drupal\athena_utility\AthenaUtility
   */
  protected $athenaUtility;

  /**
   * An instance of the core HTTP Client service.
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * AthenaLeagueUtility constructor.
   *
   * @param \Drupal\athena_utility\AthenaUtility $athenaUtility
   *   An instance of the Athena Utility.
   * @param \GuzzleHttp\Client $client
   *   An instance of the core HTTP Client service.
   */
  public function __construct(AthenaUtility $athenaUtility, Client $client) {
    $this->athenaUtility = $athenaUtility;
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('athena_utility.utility'),
      $container->get('http_client')
    );
  }

  /**
   * Generates a URL for the given base API and params.
   *
   * @param string $base
   *   The base API url.
   * @param array $params
   *   Any params for the base.
   *
   * @return string
   *   The generated URL.
   */
  public function generateApiUrl($base, array $params) {
    $url = 'https://';
    $config = $this->getLeagueConfig();
    $region = $config->get('region');
    $api_key = $config->get('api_key');

    $url .= $region . '.' . self::API_WEBSITE;

    // Replace each {param} with given param.
    if (strpos($base, '{') && !empty($params)) {
      foreach ($params as $key => $param) {
        $base = str_replace('{' . $key . '}', $param, $base);
      }
    }

    $url .= $base;

    $url .= '?api_key=' . $api_key;

    return $url;
  }

  /**
   * Gets the league settings config file.
   *
   * @return \Drupal\Core\Config\Config
   *   A config file.
   */
  public function getLeagueConfig() {
    $config = $this->athenaUtility->getConfig('athena_utility.league_settings');
    return $config;
  }

  /**
   * Gets the results from a given API.
   *
   * @param string $url
   *   The url of the API to get results from.
   *
   * @return mixed|string
   *   The response.
   */
  public function getApiResults($url) {
    try {
      $response = $this->client->get($url);
      $contents = $response->getBody()->getContents();
      $contents = json_decode($contents, TRUE);
    }
    catch (ClientException $e) {
      $contents = $e->getResponse()->getBody()->getContents();
      $contents = json_decode($contents, TRUE);
      $this->athenaUtility->setLogMessage('athena_utility', $url . ': ' . print_r($contents, TRUE), 'error');
    }

    return $contents;
  }

  /**
   * Gets the match history for the given user.
   *
   * @param string|int $uid
   *   The uid of the user.
   *
   * @return mixed|string
   *   The API results.
   */
  public function getMatchHistory($uid) {
    $account_id = $this->getAccountId($uid);
    $url = $this->generateApiUrl(self::MATCHES_BY_ACCOUNT_ID, ['account_id' => $account_id]);
    $results = $this->getApiResults($url);

    $matches = $results;

    return $matches;
  }

  /**
   * Gets the account ID from the given user.
   *
   * @param string|int $uid
   *   The uid of the user.
   *
   * @return null|string
   *   The account ID.
   */
  public function getAccountId($uid) {
    $account_id = NULL;
    $user = $this->athenaUtility->loadUser($uid);

    if ($user != NULL) {
      $account_id_field = $user->get('field_athena_league_account_id')->getValue();

      if (!empty($account_id_field)) {
        $account_id = $account_id_field[0]['value'];
      }
    }

    return $account_id;
  }

  /**
   * Gets the PUUID of the given user.
   *
   * @param string|int $uid
   *   The uid of the user.
   *
   * @return null|string
   *   The PUUID.
   */
  public function getPuuid($uid) {
    $puuid = NULL;
    $user = $this->athenaUtility->loadUser($uid);

    if ($user != NULL) {
      $puuid_field = $user->get('field_athena_league_puuid')->getValue();

      if (!empty($puuid_field)) {
        $puuid = $puuid_field[0]['value'];
      }
    }

    return $puuid;
  }

  /**
   * Gets the summoner name of the given user.
   *
   * @param string|int $uid
   *   The uid of the user.
   *
   * @return null|string
   *   The summoner name.
   */
  public function getSummonerName($uid) {
    $summoner_name = NULL;
    $user = $this->athenaUtility->loadUser($uid);

    if ($user != NULL) {
      $summoner_name_field = $user->get('field_athena_league_summ_name')->getValue();

      if (!empty($summoner_name_field)) {
        $summoner_name = $summoner_name_field[0]['value'];
      }
    }

    return $summoner_name;
  }

  /**
   * Gets a summoner's details by summoner name.
   *
   * @param string $name
   *   The summoner name.
   *
   * @return mixed|string
   *   The API results.
   */
  public function getSummonerByName($name) {
    $url = $this->generateApiUrl(self::SUMMONER_BY_NAME, ['name' => $name]);

    $results = $this->getApiResults($url);

    return $results;
  }

  /**
   * Gets a summoner's details by PUUID.
   *
   * @param string $puuid
   *   The PUUID.
   *
   * @return mixed|string
   *   The API results.
   */
  public function getSummonerByPuuid($puuid) {
    $url = $this->generateApiUrl(self::SUMMONER_BY_PUUID, ['puuid' => $puuid]);

    $results = $this->getApiResults($url);

    return $results;
  }

  /**
   * Gets match details for the given match.
   *
   * @param string $match_id
   *   The match ID.
   *
   * @return mixed|string
   *   The API results.
   */
  public function getMatchDetails($match_id) {
    $url = $this->generateApiUrl(self::MATCH_BY_MATCH_ID, ['match_id' => $match_id]);

    $results = $this->getApiResults($url);

    return $results;
  }

  public function getUserStatsEntities($uid, $game_mode = NULL) {
    $stats_entities = [];
    $account_id = $this->getAccountId($uid);
    $stats_storage = $this->athenaUtility->getEntityStorage('stats');
    $query = $stats_storage->getQuery();
    $query->condition('account_id', $account_id);

    if ($game_mode != NULL) {
      $query->condition('game_mode', $game_mode);
    }

    $ids = $query->execute();

    if (!empty($ids)) {
      $stats_entities = $stats_storage->loadMultiple($ids);
    }

    return $stats_entities;
  }

  public function getUserCpmdEntities($uid, $game_mode = NULL) {
    $cpmd_entities = [];
    $account_id = $this->getAccountId($uid);
    $cpmd_storage = $this->athenaUtility->getEntityStorage('creeps_per_min_deltas');
    $query = $cpmd_storage->getQuery();
    $query->condition('account_id', $account_id);

    if ($game_mode != NULL) {
      $query->condition('game_mode', $game_mode);
    }

    $ids = $query->execute();

    if (!empty($ids)) {
      $cpmd_entities = $cpmd_storage->loadMultiple($ids);
    }

    return $cpmd_entities;
  }

  /**
   * Gets an array of match stats details for the given user.
   *
   * @param string|int $uid
   *   The uid of the user.
   *
   * @return array
   *   An array of stats data.
   */
  public function getUserStatsDetails($uid) {
    $stats_entities = $this->getUserStatsEntities($uid);

    if (!empty($stats_entities)) {
      $stats_details = [
        'total' => [
          'total_damage_dealt' => $this->getAverageTotalDamageDealt($stats_entities),
          'kills' => $this->getAverageKills($stats_entities),
          'deaths' => $this->getAverageDeaths($stats_entities),
          'assists' => $this->getAverageAssists($stats_entities),
          'gold_earned' => $this->getAverageGoldEarned($stats_entities),
          'win' => $this->getAverageWinPercent($stats_entities),
          'matches_analyzed' => $this->getMatchesAnalyzed($stats_entities),
        ],
      ];
    }
    else {
      $stats_details = [];
    }

    return $stats_details;
  }

  /**
   * Gets the average total damage dealt for the given stats nodes.
   *
   * @param array $stats_entities
   *   An array of stats entities.
   *
   * @return float|int
   *   Average total damage dealt.
   */
  public function getAverageTotalDamageDealt(array $stats_entities) {
    $damage = 0;
    $total_entities = count($stats_entities);

    if (!empty($stats_entities)) {
      foreach ($stats_entities as $stats) {
        $damage += (int) $stats->total_damage_dealt->value;
      }
    }

    if ($total_entities > 0 && $damage > 0) {
      $damage = $damage / $total_entities;
    }

    return round($damage, 2);
  }

  public function getAveragePhysicalDamageDealt(array $stats_entities) {

  }

  public function getAverageMagicalDamageDealt(array $stats_entities) {

  }

  public function getAverageTrueDamageDealt(array $stats_entities) {

  }

  public function getAverageFirstBlood(array $stats_entities) {

  }

  public function getAverageFirstTower(array $stats_entities) {

  }

  public function getAverageDoubleKills(array $stats_entities) {

  }

  public function getAverageTripleKills(array $stats_entities) {

  }

  public function getAverageQuadraKills(array $stats_entities) {

  }

  public function getAveragePentaKills(array $stats_entities) {

  }

  public function getAverageChampLevel(array $stats_entities) {

  }

  public function getAverageCpmd(array $cpmd_entities) {

  }

  /**
   * Gets the average kills for the given stats nodes.
   *
   * @param array $stats_entities
   *   An array of stats entities.
   *
   * @return float|int
   *   Average kills.
   */
  public function getAverageKills(array $stats_entities) {
    $kills = 0;
    $total_entities = count($stats_entities);

    if (!empty($stats_entities)) {
      foreach ($stats_entities as $stats) {
        $kills += (int) $stats->kills->value;
      }
    }

    if ($total_entities > 0 && $kills > 0) {
      $kills = $kills / $total_entities;
    }

    return round($kills, 2);
  }

  /**
   * Gets the average deaths for the given stats nodes.
   *
   * @param array $stats_entities
   *   An array of stats entities.
   *
   * @return float|int
   *   Average deaths.
   */
  public function getAverageDeaths(array $stats_entities) {
    $deaths = 0;
    $total_entities = count($stats_entities);

    if (!empty($stats_entities)) {
      foreach ($stats_entities as $stats) {
        $deaths += (int) $stats->deaths->value;
      }
    }

    if ($total_entities > 0 && $deaths > 0) {
      $deaths = $deaths / $total_entities;
    }

    return round($deaths, 2);
  }

  /**
   * Gets the average assists for the given stats nodes.
   *
   * @param array $stats_entities
   *   An array of stats entities.
   *
   * @return float|int
   *   Average assists.
   */
  public function getAverageAssists(array $stats_entities) {
    $assists = 0;
    $total_entities = count($stats_entities);

    if (!empty($stats_entities)) {
      foreach ($stats_entities as $stats) {
        $assists += (int) $stats->assists->value;
      }
    }

    if ($total_entities > 0 && $assists > 0) {
      $assists = $assists / $total_entities;
    }

    return round($assists, 2);
  }

  /**
   * Gets the average gold earned for the given stats nodes.
   *
   * @param array $stats_entities
   *   An array of stats entities.
   *
   * @return float|int
   *   Average gold earned.
   */
  public function getAverageGoldEarned(array $stats_entities) {
    $gold_earned = 0;
    $total_entities = count($stats_entities);

    if (!empty($stats_entities)) {
      foreach ($stats_entities as $stats) {
        $gold_earned += (int) $stats->gold_earned->value;
      }
    }

    if ($total_entities > 0 && $gold_earned > 0) {
      $gold_earned = $gold_earned / $total_entities;
    }

    return round($gold_earned, 2);
  }

  /**
   * Gets the average win percent for the given stats entities.
   *
   * @param array $stats_entities
   *   An array of stats entities.
   *
   * @return array
   *   Average win percent.
   */
  public function getAverageWinPercent(array $stats_entities) {
    $wins = 0;
    $losses = 0;
    $total_entities = count($stats_entities);

    if (!empty($stats_entities)) {
      foreach ($stats_entities as $stats) {
        if ($stats->win->value == TRUE) {
          $wins += 1;
        }
        else {
          $losses += 1;
        }
      }
    }

    if ($wins > 0) {
      if ($losses > 0) {
        $win = $wins / $total_entities;
      }
      else {
        $win = 100;
      }
    }
    else {
      $win = 0;
    }

    $return_array = [
      'percent' => $win,
      'wins' => $wins,
      'losses' => $losses,
    ];

    return $return_array;
  }

  /**
   * Gets the number of matches analyzed.
   *
   * @param array $stats_entities
   *   An array of stats entities.
   *
   * @return int
   *   The number of matches.
   */
  public function getMatchesAnalyzed(array $stats_entities) {
    return count($stats_entities);
  }

  /**
   * Gets any cache tags to invalidate for the user on updates.
   *
   * @param string|int $uid
   *   The uid of the user.
   *
   * @return array
   *   An array of cache tags.
   */
  public function getCacheTagsToInvalidate($uid) {
    $puuid = $this->getPuuid($uid);
    $cache_tags = [];

    $stats_cache_tag = 'athena_utility_league_stats_' . $puuid;

    $summoner_cache_tag = 'athena_utility_league_summoner_' . $puuid;

    $static_cache_tag = 'athena_utility_league_static';

    $cache_tags[] = $stats_cache_tag;
    $cache_tags[] = $summoner_cache_tag;
    $cache_tags[] = $static_cache_tag;

    return $cache_tags;
  }

  /**
   * Updates the user with gathered summoner details.
   *
   * @param string|int $uid
   *   The uid of the user.
   * @param array $summoner_details
   *   An array of summoner details.
   */
  public function updateSummonerDetails($uid, array $summoner_details) {
    $user = $this->athenaUtility->loadUser($uid);

    if (!empty($summoner_details)) {
      if (isset($summoner_details['name']) && isset($summoner_details['accountId']) && isset($summoner_details['id']) && isset($summoner_details['summonerLevel']) && isset($summoner_details['profileIconId']) && isset($summoner_details['puuid'])) {
        $user->field_athena_league_summ_name = $summoner_details['name'];
        $user->field_athena_league_account_id = $summoner_details['accountId'];
        $user->field_athena_league_id = $summoner_details['id'];
        $user->field_athena_league_level = $summoner_details['summonerLevel'];
        $user->field_athena_league_profile_icon = $summoner_details['profileIconId'];
        $user->field_athena_league_puuid = $summoner_details['puuid'];

        try {
          $user->save();
        }
        catch (EntityStorageException $e) {
          $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 113.', 'error');
        }
      }
    }
  }

  public function getUserSummonerDetails($uid) {
    $user = $this->athenaUtility->loadUser($uid);
    $summoner_details = [];

    $summoner_name = $user->field_athena_league_summ_name->value;
    $level = $user->field_athena_league_level->value;
    $profile_icon = $user->field_athena_league_profile_icon->value;

    $summoner_details['summoner_name'] = isset($summoner_name) ? $summoner_name : '';
    $summoner_details['level'] = isset($level) ? $level : '';
    $summoner_details['profile_icon'] = isset($profile_icon) ? $profile_icon : '';

    return $summoner_details;
  }

  public function getStaticData() {
    $url = self::DATA_DRAGON_URL . '/realms/na.json';
    $static_data = $this->getApiResults($url);
    return $static_data;
  }

}
