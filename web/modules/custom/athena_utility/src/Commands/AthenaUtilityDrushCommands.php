<?php

namespace Drupal\athena_utility\Commands;

use Drupal\Core\Entity\EntityStorageException;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\athena_utility\AthenaUtility;
use Drupal\athena_utility\AthenaLeagueUtility;

/**
 * Class AthenaUtilityDrushCommands.
 *
 * @package Drupal\athena_utility\Commands
 */
class AthenaUtilityDrushCommands extends DrushCommands {

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
   * AthenaUtilityDrushCommands constructor.
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
   * Get match history for users.
   *
   * @command athena_utility:updateMatchHistory
   */
  public function updateMatchHistory() {
    $user_storage = $this->athenaUtility->getEntityStorage('user');
    $query = $user_storage->getQuery();
    $query->condition('status', 1);
    $query->exists('field_athena_league_id');
    $query->condition('field_athena_league_caching', FALSE);
    $uids = array_values($query->execute());

    if (!empty($uids)) {
      $batch_file = drupal_get_path('module', 'athena_utility') . '/src/update_match_history.batch.inc';

      $operations = [];
      $batch = [
        'title' => t('Update Match History'),
        'init_message' => t('Updating match history...'),
        'progress_message' => t('Completed @current processes of @total.'),
        'error_message' => t('An error occurred with the batch process.'),
        'file' => $batch_file,
        'finished' => 'finished_batch_update_match_history',
      ];

      // SET CACHING VARIABLE.
      foreach ($uids as $uid) {
        $user = $this->athenaUtility->loadUser($uid);
        $user->set('field_athena_league_caching', TRUE);

        try {
          $user->save();
        }
        catch (EntityStorageException $e) {
          $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 72.', 'error');
        }
      }

      // CACHE NEW.
      foreach ($uids as $uid) {
        $operations[] = [
          'batch_update_user_account',
          [
            $uid,
          ],
        ];

        $matches = $this->leagueUtility->getMatchHistory($uid);

        if (isset($matches['matches']) && !empty($matches['matches'])) {
          foreach ($matches['matches'] as $match) {
            $operations[] = [
              'batch_update_match',
              [
                $uid,
                $match,
              ],
            ];
          }
        }

        $operations[] = [
          'batch_set_cache_stats',
          [
            $uid,
          ],
        ];

        $operations[] = [
          'batch_set_cache_summoner',
          [
            $uid,
          ],
        ];

        $operations[] = [
          'batch_set_cache_finished',
          [
            $uid,
          ],
        ];
      }

      $operations[] = [
        'batch_set_cache_static',
        [],
      ];

      if (!empty($operations)) {
        $batch['operations'] = $operations;

        batch_set($batch);

        drush_backend_batch_process();
      }
    }
  }

  /**
   * Deletes all content.
   *
   * @command athena_utility:deleteAllContent
   */
  public function deleteAllContent() {
    $game_storage = $this->athenaUtility->getEntityStorage('game');
    $query = $game_storage->getQuery();
    $ids = $query->execute();
    $games = $game_storage->loadMultiple($ids);

    try {
      $game_storage->delete($games);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 401.');
    }

    $team_storage = $this->athenaUtility->getEntityStorage('team');
    $query = $team_storage->getQuery();
    $ids = $query->execute();
    $teams = $team_storage->loadMultiple($ids);

    try {
      $team_storage->delete($teams);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 402.');
    }

    $stats_storage = $this->athenaUtility->getEntityStorage('stats');
    $query = $stats_storage->getQuery();
    $ids = $query->execute();
    $stats = $stats_storage->loadMultiple($ids);

    try {
      $stats_storage->delete($stats);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 403.');
    }

    $cpmd_storage = $this->athenaUtility->getEntityStorage('creeps_per_min_deltas');
    $query = $cpmd_storage->getQuery();
    $ids = $query->execute();
    $cpmd = $cpmd_storage->loadMultiple($ids);

    try {
      $cpmd_storage->delete($cpmd);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 404.');
    }

    $gpmd_storage = $this->athenaUtility->getEntityStorage('gold_per_min_deltas');
    $query = $gpmd_storage->getQuery();
    $ids = $query->execute();
    $gpmd = $gpmd_storage->loadMultiple($ids);

    try {
      $gpmd_storage->delete($gpmd);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 405.');
    }

    $cdpmd_storage = $this->athenaUtility->getEntityStorage('cs_diff_per_min_deltas');
    $query = $cdpmd_storage->getQuery();
    $ids = $query->execute();
    $cdpmd = $cdpmd_storage->loadMultiple($ids);

    try {
      $cdpmd_storage->delete($cdpmd);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 406.');
    }

    $xdpmd_storage = $this->athenaUtility->getEntityStorage('xp_diff_per_min_deltas');
    $query = $xdpmd_storage->getQuery();
    $ids = $query->execute();
    $xdpmd = $xdpmd_storage->loadMultiple($ids);

    try {
      $xdpmd_storage->delete($xdpmd);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 407.');
    }

    $dtpmd_storage = $this->athenaUtility->getEntityStorage('damage_taken_per_min_deltas');
    $query = $dtpmd_storage->getQuery();
    $ids = $query->execute();
    $dtpmd = $dtpmd_storage->loadMultiple($ids);

    try {
      $dtpmd_storage->delete($dtpmd);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 408.');
    }

    $dtdpmd_storage = $this->athenaUtility->getEntityStorage('damage_taken_diff_per_min_deltas');
    $query = $dtpmd_storage->getQuery();
    $ids = $query->execute();
    $dtdpmd = $dtdpmd_storage->loadMultiple($ids);

    try {
      $dtdpmd_storage->delete($dtdpmd);
    }
    catch (EntityStorageException $e) {
      $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 409.');
    }
  }

  /**
   * Deletes all nodes.
   *
   * @command athena_utility:deleteAllNodes
   */
  public function deleteAllNodes() {
    $node_storage = $this->athenaUtility->getEntityStorage('node');
    $query = $node_storage->getQuery();
    $query->condition('status', 1);
    $nids = array_values($query->execute());

    if (!empty($nids)) {
      $batch_file = drupal_get_path('module', 'athena_utility') . '/src/delete_all_nodes.batch.inc';

      $operations = [];
      $batch = [
        'title' => t('Delete All Nodes'),
        'init_message' => t('Deleting nodes...'),
        'progress_message' => t('Deleted @current nodes of @total.'),
        'error_message' => t('An error occurred with the batch process.'),
        'file' => $batch_file,
      ];

      foreach ($nids as $nid) {
        $operations[] = [
          'batch_delete_node',
          [
            $nid,
          ],
        ];
      }

      if (!empty($operations)) {
        $batch['operations'] = $operations;

        batch_set($batch);

        drush_backend_batch_process();
      }
    }
  }

  /**
   * Get match history for users.
   *
   * @command athena_utility:deleteAllContentTypes
   */
  public function deleteAllContentTypes() {
    $node_type_storage = $this->athenaUtility->getEntityStorage('node_type');
    $query = $node_type_storage->getQuery();
    $ids = array_values($query->execute());

    foreach ($ids as $id) {
      if ($id != 'article' && $id != 'page') {
        $content_type = $node_type_storage->load($id);
        try {
          $content_type->delete();
        }
        catch (EntityStorageException $e) {
          $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 23.', 'error');
        }
      }
    }
  }

  /**
   * Create test games.
   *
   * @command athena_utility:createGames
   */
  public function createGames() {
    $this->athenaUtility->setLogMessage('athena_utility', 'Start Create: ' . time());

    $game_storage = $this->athenaUtility->getEntityStorage('game');

    for ($i = 0; $i < 1000; $i++) {
      $game = $game_storage->create([
        'name' => 'Test',
        'uid' => 1,
        'status' => 1,
        'game_id' => '123456789',
        'test1' => 1,
        'test2' => 2,
        'test3' => 3,
        'test4' => 4,
        'test5' => 5,
        'test6' => 6,
        'test7' => 7,
        'test8' => 8,
        'test9' => 9,
        'test10' => 10,
        'test11' => 11,
        'test12' => 12,
        'test13' => 13,
        'test14' => 14,
        'test15' => 15,
        'test16' => 16,
        'test17' => 17,
        'test18' => 18,
        'test19' => 19,
        'test20' => 20,
        'test21' => 21,
        'test22' => 22,
        'test23' => 23,
        'test24' => 24,
        'test25' => 25,
        'test26' => 26,
        'test27' => 27,
        'test28' => 28,
        'test29' => 29,
        'test30' => 30,
        'test31' => 31,
        'test32' => 32,
        'test33' => 33,
        'test34' => 34,
        'test35' => 35,
        'test36' => 36,
        'test37' => 37,
        'test38' => 38,
        'test39' => 39,
        'test40' => 40,
        'test41' => 41,
        'test42' => 42,
        'test43' => 43,
        'test44' => 44,
        'test45' => 45,
        'test46' => 46,
        'test47' => 47,
        'test48' => 48,
        'test49' => 49,
        'test50' => 50,
      ]);

      try {
        $game->save();
      }
      catch (EntityStorageException $e) {
        $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 317.', 'error');
      }
    }

    $this->athenaUtility->setLogMessage('athena_utility', 'End Create: ' . time());
  }

  /**
   * Delete test games.
   *
   * @command athena_utility:deleteGames
   */
  public function deleteGames() {
    $this->athenaUtility->setLogMessage('athena_utility', 'Start Delete: ' . time());

    $game_storage = $this->athenaUtility->getEntityStorage('game');
    $query = $game_storage->getQuery();
    $games = $query->execute();
    $games = $game_storage->loadMultiple($games);

    foreach ($games as $game) {
      try {
        $game->delete();
      }
      catch (EntityStorageException $e) {
        $this->athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 318.', 'error');
      }
    }

    $this->athenaUtility->setLogMessage('athena_utility', 'End Delete: ' . time());
  }

  /**
   * Create all required fields.
   *
   * @command athena_utility:createFields
   */
  public function createFields() {
    $game_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
    ];

    $team_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'win' => 'string_long',
      'first_blood' => 'boolean',
      'first_tower' => 'boolean',
      'first_inhibitor' => 'boolean',
      'first_baron' => 'boolean',
      'first_dragon' => 'boolean',
      'first_rift_herald' => 'boolean',
      'tower_kills' => 'integer',
      'inhibitor_kills' => 'integer',
      'baron_kills' => 'integer',
      'dragon_kills' => 'integer',
      'vilemaw_kills' => 'integer',
      'rift_herald_kills' => 'integer',
      'dominion_victory_score' => 'integer',
      'ban_1' => 'integer',
      'ban_1_turn' => 'integer',
      'ban_2' => 'integer',
      'ban_2_turn' => 'integer',
      'ban_3' => 'integer',
      'ban_3_turn' => 'integer',
      'ban_4' => 'integer',
      'ban_4_turn' => 'integer',
      'ban_5' => 'integer',
      'ban_5_turn' => 'integer',
    ];

    $stats_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'team_id' => 'integer',
      'participant_id' => 'integer',
      'champion_id' => 'integer',
      'spell_1_id' => 'integer',
      'spell_2_id' => 'integer',
      'highest_achieved_season_tier' => 'string_long',
      'win' => 'boolean',
      'item_0' => 'integer',
      'item_1' => 'integer',
      'item_2' => 'integer',
      'item_3' => 'integer',
      'item_4' => 'integer',
      'item_5' => 'integer',
      'item_6' => 'integer',
      'kills' => 'integer',
      'deaths' => 'integer',
      'assists' => 'integer',
      'largest_killing_spree' => 'integer',
      'largest_multi_kill' => 'integer',
      'killing_sprees' => 'integer',
      'longest_time_spent_living' => 'integer',
      'double_kills' => 'integer',
      'triple_kills' => 'integer',
      'quadra_kills' => 'integer',
      'penta_kills' => 'integer',
      'unreal_kills' => 'integer',
      'total_damage_dealt' => 'string_long',
      'magic_damage_dealt' => 'string_long',
      'physical_damage_dealt' => 'string_long',
      'true_damage_dealt' => 'string_long',
      'largest_critical_strike' => 'integer',
      'total_damage_dealt_to_champs' => 'string_long',
      'magic_damage_dealt_to_champs' => 'string_long',
      'physical_damage_dealt_to_champs' => 'string_long',
      'true_damage_dealt_to_champs' => 'string_long',
      'total_heal' => 'string_long',
      'total_units_healed' => 'integer',
      'damage_self_mitigated' => 'string_long',
      'damage_dealt_to_objectives' => 'string_long',
      'damage_dealt_to_turrets' => 'string_long',
      'vision_score' => 'string_long',
      'time_ccing_others' => 'string_long',
      'total_damage_taken' => 'string_long',
      'magical_damage_taken' => 'string_long',
      'physical_damage_taken' => 'string_long',
      'true_damage_taken' => 'string_long',
      'gold_earned' => 'integer',
      'gold_spent' => 'integer',
      'turret_kills' => 'integer',
      'inhibitor_kills' => 'integer',
      'total_minions_killed' => 'integer',
      'neutral_minions_killed' => 'integer',
      'neutral_minions_killed_team' => 'integer',
      'neutral_minions_killed_enemy' => 'integer',
      'total_time_crowd_control_dealt' => 'integer',
      'champ_level' => 'integer',
      'vision_wards_bought_in_game' => 'integer',
      'sight_wards_bought_in_game' => 'integer',
      'wards_placed' => 'integer',
      'wards_killed' => 'integer',
      'first_blood_kill' => 'boolean',
      'first_blood_assist' => 'boolean',
      'first_tower_kill' => 'boolean',
      'first_tower_assist' => 'boolean',
      'first_inhibitor_kill' => 'boolean',
      'first_inhibitor_assist' => 'boolean',
      'combat_player_score' => 'integer',
      'objective_player_score' => 'integer',
      'total_player_score' => 'integer',
      'total_score_rank' => 'integer',
      'player_score_0' => 'integer',
      'player_score_1' => 'integer',
      'player_score_2' => 'integer',
      'player_score_3' => 'integer',
      'player_score_4' => 'integer',
      'player_score_5' => 'integer',
      'player_score_6' => 'integer',
      'player_score_7' => 'integer',
      'player_score_8' => 'integer',
      'player_score_9' => 'integer',
      'perk_0' => 'integer',
      'perk_0_var_1' => 'integer',
      'perk_0_var_2' => 'integer',
      'perk_0_var_3' => 'integer',
      'perk_1' => 'integer',
      'perk_1_var_1' => 'integer',
      'perk_1_var_2' => 'integer',
      'perk_1_var_3' => 'integer',
      'perk_2' => 'integer',
      'perk_2_var_1' => 'integer',
      'perk_2_var_2' => 'integer',
      'perk_2_var_3' => 'integer',
      'perk_3' => 'integer',
      'perk_3_var_1' => 'integer',
      'perk_3_var_2' => 'integer',
      'perk_3_var_3' => 'integer',
      'perk_4' => 'integer',
      'perk_4_var_1' => 'integer',
      'perk_4_var_2' => 'integer',
      'perk_4_var_3' => 'integer',
      'perk_5' => 'integer',
      'perk_5_var_1' => 'integer',
      'perk_5_var_2' => 'integer',
      'perk_5_var_3' => 'integer',
      'perk_primary_style' => 'integer',
      'perk_sub_style' => 'integer',
      'stat_perk_0' => 'integer',
      'stat_perk_1' => 'integer',
      'stat_perk_2' => 'integer',
      'role' => 'string_long',
      'lane' => 'string_long',
      'node_neutralize' => 'integer',
      'node_neutralize_assist' => 'integer',
      'node_capture' => 'integer',
      'node_capture_assist' => 'integer',
      'altars_neutralized' => 'integer',
      'altars_captured' => 'integer',
    ];

    $creeps_per_min_deltas_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'time' => 'string_long',
      'value' => 'string_long',
    ];

    $gold_per_min_deltas_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'time' => 'string_long',
      'value' => 'string_long',
    ];

    $cs_diff_per_min_deltas_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'time' => 'string_long',
      'value' => 'string_long',
    ];

    $xp_diff_per_min_deltas_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'time' => 'string_long',
      'value' => 'string_long',
    ];

    $damage_taken_per_min_deltas_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'time' => 'string_long',
      'value' => 'string_long',
    ];

    $damage_taken_diff_per_min_deltas_fields = [
      'platform_id' => 'string_long',
      'game_creation' => 'string_long',
      'game_duration' => 'string_long',
      'queue_id' => 'integer',
      'map_id' => 'integer',
      'season_id' => 'integer',
      'game_version' => 'string_long',
      'game_mode' => 'string_long',
      'game_type' => 'string_long',
      'time' => 'string_long',
      'value' => 'string_long',
    ];

    foreach ($game_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('game', NULL, $field_name, $field_type);
    }

    foreach ($team_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('team', NULL, $field_name, $field_type);
    }

    foreach ($stats_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('stats', NULL, $field_name, $field_type);
    }

    foreach ($creeps_per_min_deltas_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('creeps_per_min_deltas', NULL, $field_name, $field_type);
    }

    foreach ($gold_per_min_deltas_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('gold_per_min_deltas', NULL, $field_name, $field_type);
    }

    foreach ($cs_diff_per_min_deltas_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('cs_diff_per_min_deltas', NULL, $field_name, $field_type);
    }

    foreach ($xp_diff_per_min_deltas_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('xp_diff_per_min_deltas', NULL, $field_name, $field_type);
    }

    foreach ($damage_taken_per_min_deltas_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('damage_taken_per_min_deltas', NULL, $field_name, $field_type);
    }

    foreach ($damage_taken_diff_per_min_deltas_fields as $field_name => $field_type) {
      $this->athenaUtility->getField('damage_taken_diff_per_min_deltas', NULL, $field_name, $field_type);
    }
  }

}
