<?php

/**
 * @file
 * Batch functions for updating match information.
 */

use Drupal\Core\Entity\EntityStorageException;

function batch_update_match($uid, $match) {
  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');
  /* @var $leagueUtility \Drupal\athena_utility\AthenaLeagueUtility */ $leagueUtility = \Drupal::service('athena_utility.league_utility');
  $game_storage = $athenaUtility->getEntityStorage('game');
  $game_query = $game_storage->getQuery();
  $game_query->condition('game_id', $match['gameId']);
  $game_test = $game_query->execute();

  if (empty($game_test)) {
    $team_storage = $athenaUtility->getEntityStorage('team');
    $stats_storage = $athenaUtility->getEntityStorage('stats');
    $creeps_per_min_deltas_storage = $athenaUtility->getEntityStorage('creeps_per_min_deltas');
    $gold_per_min_deltas_storage = $athenaUtility->getEntityStorage('gold_per_min_deltas');
    $cs_diff_per_min_deltas_storage = $athenaUtility->getEntityStorage('cs_diff_per_min_deltas');
    $xp_diff_per_min_deltas_storage = $athenaUtility->getEntityStorage('xp_diff_per_min_deltas');
    $damage_taken_per_min_deltas_storage = $athenaUtility->getEntityStorage('damage_taken_per_min_deltas');
    $damage_taken_diff_per_min_deltas_storage = $athenaUtility->getEntityStorage('damage_taken_diff_per_min_deltas');

    $game_details = $leagueUtility->getMatchDetails($match['gameId']);

    if (isset($game_details['gameId'])) {
      $game = $game_storage->create([
        'name' => microtime(),
        'uid' => 1,
        'status' => 1,
        'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
        'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
        'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
        'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
        'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
        'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
        'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
        'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
        'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
        'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
      ]);

      $teams = [];

      foreach ($game_details['teams'] as $team_details) {
        $team = $team_storage->create([
          'name' => microtime(),
          'uid' => 1,
          'status' => 1,
          'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
          'team_id' => isset($team_details['teamId']) ? $team_details['teamId'] : NULL,
          'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
          'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
          'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
          'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
          'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
          'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
          'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
          'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
          'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
          'win' => isset($team_details['win']) ? $team_details['win'] : NULL,
          'first_blood' => isset($team_details['firstBlood']) ? $team_details['firstBlood'] : NULL,
          'first_tower' => isset($team_details['firstTower']) ? $team_details['firstTower'] : NULL,
          'first_inhibitor' => isset($team_details['firstInhibitor']) ? $team_details['firstInhibitor'] : NULL,
          'first_baron' => isset($team_details['firstBaron']) ? $team_details['firstBaron'] : NULL,
          'first_dragon' => isset($team_details['firstDragon']) ? $team_details['firstDragon'] : NULL,
          'first_rift_herald' => isset($team_details['firstRiftHerald']) ? $team_details['firstRiftHerald'] : NULL,
          'tower_kills' => isset($team_details['towerKills']) ? $team_details['towerKills'] : NULL,
          'inhibitor_kills' => isset($team_details['inhibitorKills']) ? $team_details['inhibitorKills'] : NULL,
          'baron_kills' => isset($team_details['baronKills']) ? $team_details['baronKills'] : NULL,
          'dragon_kills' => isset($team_details['dragonKills']) ? $team_details['dragonKills'] : NULL,
          'vilemaw_kills' => isset($team_details['vilemawKills']) ? $team_details['vilemawKills'] : NULL,
          'rift_herald_kills' => isset($team_details['riftHeraldKills']) ? $team_details['riftHeraldKills'] : NULL,
          'dominion_victory_score' => isset($team_details['dominionVictoryScore']) ? $team_details['dominionVictoryScore'] : NULL,
          'ban_1' => isset($team_details['bans'][0]) ? $team_details['bans'][0]['championId'] : NULL,
          'ban_1_turn' => isset($team_details['bans'][0]) ? $team_details['bans'][0]['pickTurn'] : NULL,
          'ban_2' => isset($team_details['bans'][1]) ? $team_details['bans'][1]['championId'] : NULL,
          'ban_2_turn' => isset($team_details['bans'][1]) ? $team_details['bans'][1]['pickTurn'] : NULL,
          'ban_3' => isset($team_details['bans'][2]) ? $team_details['bans'][2]['championId'] : NULL,
          'ban_3_turn' => isset($team_details['bans'][2]) ? $team_details['bans'][2]['pickTurn'] : NULL,
          'ban_4' => isset($team_details['bans'][3]) ? $team_details['bans'][3]['championId'] : NULL,
          'ban_4_turn' => isset($team_details['bans'][3]) ? $team_details['bans'][3]['pickTurn'] : NULL,
          'ban_5' => isset($team_details['bans'][4]) ? $team_details['bans'][4]['championId'] : NULL,
          'ban_5_turn' => isset($team_details['bans'][4]) ? $team_details['bans'][4]['pickTurn'] : NULL,
        ]);

        $teams[] = $team;
      }

      $participants = [];
      $creeps_per_min_deltas_array = [];
      $gold_per_min_deltas_array = [];
      $cs_diff_per_min_deltas_array = [];
      $xp_diff_per_min_deltas_array = [];
      $damage_taken_per_min_deltas_array = [];
      $damage_taken_diff_per_min_deltas_array = [];

      foreach ($game_details['participants'] as $participant_details) {
        $stats = $stats_storage->create([
          'name' => microtime(),
          'uid' => 1,
          'status' => 1,
          'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
          'account_id' => isset($game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId']) ? $game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId'] : NULL,
          'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
          'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
          'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
          'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
          'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
          'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
          'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
          'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
          'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
          'team_id' => isset($participant_details['teamId']) ? $participant_details['teamId'] : NULL,
          'participant_id' => isset($participant_details['participantId']) ? $participant_details['participantId'] : NULL,
          'champion_id' => isset($participant_details['championId']) ? $participant_details['championId'] : NULL,
          'spell_1_id' => isset($participant_details['spell1Id']) ? $participant_details['spell1Id'] : NULL,
          'spell_2_id' => isset($participant_details['spell2Id']) ? $participant_details['spell2Id'] : NULL,
          'highest_achieved_season_tier' => isset($participant_details['highestAchievedSeasonTier']) ? $participant_details['highestAchievedSeasonTier'] : NULL,
          'win' => isset($participant_details['stats']['win']) ? $participant_details['stats']['win'] : NULL,
          'item_0' => isset($participant_details['stats']['item0']) ? $participant_details['stats']['item0'] : NULL,
          'item_1' => isset($participant_details['stats']['item1']) ? $participant_details['stats']['item1'] : NULL,
          'item_2' => isset($participant_details['stats']['item2']) ? $participant_details['stats']['item2'] : NULL,
          'item_3' => isset($participant_details['stats']['item3']) ? $participant_details['stats']['item3'] : NULL,
          'item_4' => isset($participant_details['stats']['item4']) ? $participant_details['stats']['item4'] : NULL,
          'item_5' => isset($participant_details['stats']['item5']) ? $participant_details['stats']['item5'] : NULL,
          'item_6' => isset($participant_details['stats']['item6']) ? $participant_details['stats']['item6'] : NULL,
          'kills' => isset($participant_details['stats']['kills']) ? $participant_details['stats']['kills'] : NULL,
          'deaths' => isset($participant_details['stats']['deaths']) ? $participant_details['stats']['deaths'] : NULL,
          'assists' => isset($participant_details['stats']['assists']) ? $participant_details['stats']['assists'] : NULL,
          'largest_killing_spree' => isset($participant_details['stats']['largestKillingSpree']) ? $participant_details['stats']['largestKillingSpree'] : NULL,
          'largest_multi_kill' => isset($participant_details['stats']['largestMultiKill']) ? $participant_details['stats']['largestMultiKill'] : NULL,
          'killing_sprees' => isset($participant_details['stats']['killingSpree']) ? $participant_details['stats']['killingSpree'] : NULL,
          'longest_time_spent_living' => isset($participant_details['stats']['longestTimeSpentLiving']) ? $participant_details['stats']['longestTimeSpentLiving'] : NULL,
          'double_kills' => isset($participant_details['stats']['doubleKills']) ? $participant_details['stats']['doubleKills'] : NULL,
          'triple_kills' => isset($participant_details['stats']['tripleKills']) ? $participant_details['stats']['tripleKills'] : NULL,
          'quadra_kills' => isset($participant_details['stats']['quadraKills']) ? $participant_details['stats']['quadraKills'] : NULL,
          'penta_kills' => isset($participant_details['stats']['pentaKills']) ? $participant_details['stats']['pentaKills'] : NULL,
          'unreal_kills' => isset($participant_details['stats']['unrealKills']) ? $participant_details['stats']['unrealKills'] : NULL,
          'total_damage_dealt' => isset($participant_details['stats']['totalDamageDealt']) ? $participant_details['stats']['totalDamageDealt'] : NULL,
          'magic_damage_dealt' => isset($participant_details['stats']['magicDamageDealt']) ? $participant_details['stats']['magicDamageDealt'] : NULL,
          'physical_damage_dealt' => isset($participant_details['stats']['physicalDamageDealt']) ? $participant_details['stats']['physicalDamageDealt'] : NULL,
          'true_damage_dealt' => isset($participant_details['stats']['trueDamageDealt']) ? $participant_details['stats']['trueDamageDealt'] : NULL,
          'largest_critical_strike' => isset($participant_details['stats']['largestCriticalStrike']) ? $participant_details['stats']['largestCriticalStrike'] : NULL,
          'total_damage_dealt_to_champs' => isset($participant_details['stats']['totalDamageDealtToChampions']) ? $participant_details['stats']['totalDamageDealtToChampions'] : NULL,
          'magic_damage_dealt_to_champs' => isset($participant_details['stats']['magicDamageDealtToChampions']) ? $participant_details['stats']['magicDamageDealtToChampions'] : NULL,
          'physical_damage_dealt_to_champs' => isset($participant_details['stats']['physicalDamageDealtToChampions']) ? $participant_details['stats']['physicalDamageDealtToChampions'] : NULL,
          'true_damage_dealt_to_champs' => isset($participant_details['stats']['trueDamageDealtToChampions']) ? $participant_details['stats']['trueDamageDealtToChampions'] : NULL,
          'total_heal' => isset($participant_details['stats']['totalHeal']) ? $participant_details['stats']['totalHeal'] : NULL,
          'total_units_healed' => isset($participant_details['stats']['totalUnitsHealed']) ? $participant_details['stats']['totalUnitsHealed'] : NULL,
          'damage_self_mitigated' => isset($participant_details['stats']['damageSelfMitigated']) ? $participant_details['stats']['damageSelfMitigated'] : NULL,
          'damage_dealt_to_objectives' => isset($participant_details['stats']['damageDealtToObjectives']) ? $participant_details['stats']['damageDealtToObjectives'] : NULL,
          'damage_dealt_to_turrets' => isset($participant_details['stats']['damageDealtToTurrets']) ? $participant_details['stats']['damageDealtToTurrets'] : NULL,
          'vision_score' => isset($participant_details['stats']['visionScore']) ? $participant_details['stats']['visionScore'] : NULL,
          'time_ccing_others' => isset($participant_details['stats']['timeCCingOthers']) ? $participant_details['stats']['timeCCingOthers'] : NULL,
          'total_damage_taken' => isset($participant_details['stats']['totalDamageTaken']) ? $participant_details['stats']['totalDamageTaken'] : NULL,
          'magical_damage_taken' => isset($participant_details['stats']['magicalDamageTaken']) ? $participant_details['stats']['magicalDamageTaken'] : NULL,
          'physical_damage_taken' => isset($participant_details['stats']['physicalDamageTaken']) ? $participant_details['stats']['physicalDamageTaken'] : NULL,
          'true_damage_taken' => isset($participant_details['stats']['trueDamageTaken']) ? $participant_details['stats']['trueDamageTaken'] : NULL,
          'gold_earned' => isset($participant_details['stats']['goldEarned']) ? $participant_details['stats']['goldEarned'] : NULL,
          'gold_spent' => isset($participant_details['stats']['goldSpent']) ? $participant_details['stats']['goldSpent'] : NULL,
          'turret_kills' => isset($participant_details['stats']['turretKills']) ? $participant_details['stats']['turretKills'] : NULL,
          'inhibitor_kills' => isset($participant_details['stats']['inhibitorKills']) ? $participant_details['stats']['inhibitorKills'] : NULL,
          'total_minions_killed' => isset($participant_details['stats']['totalMinionsKilled']) ? $participant_details['stats']['totalMinionsKilled'] : NULL,
          'neutral_minions_killed' => isset($participant_details['stats']['neutralMinionsKilled']) ? $participant_details['stats']['neutralMinionsKilled'] : NULL,
          'neutral_minions_killed_team' => isset($participant_details['stats']['neutralMinionsKilledTeamJungle']) ? $participant_details['stats']['neutralMinionsKilledTeamJungle'] : NULL,
          'neutral_minions_killed_enemy' => isset($participant_details['stats']['neutralMinionsKilledEnemyJungle']) ? $participant_details['stats']['neutralMinionsKilledEnemyJungle'] : NULL,
          'total_time_crowd_control_dealt' => isset($participant_details['stats']['totalTimeCrowdControlDealt']) ? $participant_details['stats']['totalTimeCrowdControlDealt'] : NULL,
          'champ_level' => isset($participant_details['stats']['champLevel']) ? $participant_details['stats']['champLevel'] : NULL,
          'vision_wards_bought_in_game' => isset($participant_details['stats']['visionWardsBoughtInGame']) ? $participant_details['stats']['visionWardsBoughtInGame'] : NULL,
          'sight_wards_bought_in_game' => isset($participant_details['stats']['sightWardsBoughtInGame']) ? $participant_details['stats']['sightWardsBoughtInGame'] : NULL,
          'wards_placed' => isset($participant_details['stats']['wardsPlaced']) ? $participant_details['stats']['wardsPlaced'] : NULL,
          'wards_killed' => isset($participant_details['stats']['wardsKilled']) ? $participant_details['stats']['wardsKilled'] : NULL,
          'first_blood_kill' => isset($participant_details['stats']['firstBloodKill']) ? $participant_details['stats']['firstBloodKill'] : NULL,
          'first_blood_assist' => isset($participant_details['stats']['firstBloodAssist']) ? $participant_details['stats']['firstBloodAssist'] : NULL,
          'first_tower_kill' => isset($participant_details['stats']['firstTowerKill']) ? $participant_details['stats']['firstTowerKill'] : NULL,
          'first_tower_assist' => isset($participant_details['stats']['firstTowerAssist']) ? $participant_details['stats']['firstTowerAssist'] : NULL,
          'first_inhibitor_kill' => isset($participant_details['stats']['firstInhibitorKill']) ? $participant_details['stats']['firstInhibitorKill'] : NULL,
          'first_inhibitor_assist' => isset($participant_details['stats']['firstInhibitorAssist']) ? $participant_details['stats']['firstInhibitorAssist'] : NULL,
          'combat_player_score' => isset($participant_details['stats']['combatPlayerScore']) ? $participant_details['stats']['combatPlayerScore'] : NULL,
          'objective_player_score' => isset($participant_details['stats']['objectivePlayerScore']) ? $participant_details['stats']['objectivePlayerScore'] : NULL,
          'total_player_score' => isset($participant_details['stats']['totalPlayerScore']) ? $participant_details['stats']['totalPlayerScore'] : NULL,
          'total_score_rank' => isset($participant_details['stats']['totalScoreRank']) ? $participant_details['stats']['totalScoreRank'] : NULL,
          'player_score_0' => isset($participant_details['stats']['playerScore0']) ? $participant_details['stats']['playerScore0'] : NULL,
          'player_score_1' => isset($participant_details['stats']['playerScore1']) ? $participant_details['stats']['playerScore1'] : NULL,
          'player_score_2' => isset($participant_details['stats']['playerScore2']) ? $participant_details['stats']['playerScore2'] : NULL,
          'player_score_3' => isset($participant_details['stats']['playerScore3']) ? $participant_details['stats']['playerScore3'] : NULL,
          'player_score_4' => isset($participant_details['stats']['playerScore4']) ? $participant_details['stats']['playerScore4'] : NULL,
          'player_score_5' => isset($participant_details['stats']['playerScore5']) ? $participant_details['stats']['playerScore5'] : NULL,
          'player_score_6' => isset($participant_details['stats']['playerScore6']) ? $participant_details['stats']['playerScore6'] : NULL,
          'player_score_7' => isset($participant_details['stats']['playerScore7']) ? $participant_details['stats']['playerScore7'] : NULL,
          'player_score_8' => isset($participant_details['stats']['playerScore8']) ? $participant_details['stats']['playerScore8'] : NULL,
          'player_score_9' => isset($participant_details['stats']['playerScore9']) ? $participant_details['stats']['playerScore9'] : NULL,
          'perk_0' => isset($participant_details['stats']['perk0']) ? $participant_details['stats']['perk0'] : NULL,
          'perk_0_var_1' => isset($participant_details['stats']['perk0Var1']) ? $participant_details['stats']['perk0Var1'] : NULL,
          'perk_0_var_2' => isset($participant_details['stats']['perk0Var2']) ? $participant_details['stats']['perk0Var2'] : NULL,
          'perk_0_var_3' => isset($participant_details['stats']['perk0Var3']) ? $participant_details['stats']['perk0Var3'] : NULL,
          'perk_1' => isset($participant_details['stats']['perk1']) ? $participant_details['stats']['perk1'] : NULL,
          'perk_1_var_1' => isset($participant_details['stats']['perk1Var1']) ? $participant_details['stats']['perk1Var1'] : NULL,
          'perk_1_var_2' => isset($participant_details['stats']['perk1Var2']) ? $participant_details['stats']['perk1Var2'] : NULL,
          'perk_1_var_3' => isset($participant_details['stats']['perk1Var3']) ? $participant_details['stats']['perk1Var3'] : NULL,
          'perk_2' => isset($participant_details['stats']['perk2']) ? $participant_details['stats']['perk2'] : NULL,
          'perk_2_var_1' => isset($participant_details['stats']['perk2Var1']) ? $participant_details['stats']['perk2Var1'] : NULL,
          'perk_2_var_2' => isset($participant_details['stats']['perk2Var2']) ? $participant_details['stats']['perk2Var2'] : NULL,
          'perk_2_var_3' => isset($participant_details['stats']['perk2Var3']) ? $participant_details['stats']['perk2Var3'] : NULL,
          'perk_3' => isset($participant_details['stats']['perk3']) ? $participant_details['stats']['perk3'] : NULL,
          'perk_3_var_1' => isset($participant_details['stats']['perk3Var1']) ? $participant_details['stats']['perk3Var1'] : NULL,
          'perk_3_var_2' => isset($participant_details['stats']['perk3Var2']) ? $participant_details['stats']['perk3Var2'] : NULL,
          'perk_3_var_3' => isset($participant_details['stats']['perk3Var3']) ? $participant_details['stats']['perk3Var3'] : NULL,
          'perk_4' => isset($participant_details['stats']['perk4']) ? $participant_details['stats']['perk4'] : NULL,
          'perk_4_var_1' => isset($participant_details['stats']['perk4Var1']) ? $participant_details['stats']['perk4Var1'] : NULL,
          'perk_4_var_2' => isset($participant_details['stats']['perk4Var2']) ? $participant_details['stats']['perk4Var2'] : NULL,
          'perk_4_var_3' => isset($participant_details['stats']['perk4Var3']) ? $participant_details['stats']['perk4Var3'] : NULL,
          'perk_5' => isset($participant_details['stats']['perk5']) ? $participant_details['stats']['perk5'] : NULL,
          'perk_5_var_1' => isset($participant_details['stats']['perk5Var1']) ? $participant_details['stats']['perk5Var1'] : NULL,
          'perk_5_var_2' => isset($participant_details['stats']['perk5Var2']) ? $participant_details['stats']['perk5Var2'] : NULL,
          'perk_5_var_3' => isset($participant_details['stats']['perk5Var3']) ? $participant_details['stats']['perk5Var3'] : NULL,
          'perk_primary_style' => isset($participant_details['stats']['perkPrimaryStyle']) ? $participant_details['stats']['perkPrimaryStyle'] : NULL,
          'perk_sub_style' => isset($participant_details['stats']['perkSubStyle']) ? $participant_details['stats']['perkSubStyle'] : NULL,
          'stat_perk_0' => isset($participant_details['stats']['statPerk0']) ? $participant_details['stats']['statPerk0'] : NULL,
          'stat_perk_1' => isset($participant_details['stats']['statPerk1']) ? $participant_details['stats']['statPerk1'] : NULL,
          'stat_perk_2' => isset($participant_details['stats']['statPerk2']) ? $participant_details['stats']['statPerk2'] : NULL,
          'role' => isset($participant_details['stats']['role']) ? $participant_details['stats']['role'] : NULL,
          'lane' => isset($participant_details['stats']['lane']) ? $participant_details['stats']['lane'] : NULL,
          'node_neutralize' => isset($participant_details['stats']['nodeNeutralize']) ? $participant_details['stats']['nodeNeutralize'] : NULL,
          'node_neutralize_assist' => isset($participant_details['stats']['nodeNeutralizeAssist']) ? $participant_details['stats']['nodeNeutralizeAssist'] : NULL,
          'node_capture' => isset($participant_details['stats']['nodeCapture']) ? $participant_details['stats']['nodeCapture'] : NULL,
          'node_capture_assist' => isset($participant_details['stats']['nodeCaptureAssist']) ? $participant_details['stats']['nodeCaptureAssist'] : NULL,
          'altars_neutralized' => isset($participant_details['stats']['altarsNeutralized']) ? $participant_details['stats']['altarsNeutralized'] : NULL,
          'altars_captured' => isset($participant_details['stats']['altarsCaptured']) ? $participant_details['stats']['altarsCaptured'] : NULL,
        ]);

        if (isset($participant_details['timeline']['creepsPerMinDeltas'])) {
          foreach ($participant_details['timeline']['creepsPerMinDeltas'] as $time => $value) {
            $creeps_per_min_deltas = $creeps_per_min_deltas_storage->create([
              'name' => microtime(),
              'uid' => 1,
              'status' => 1,
              'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
              'account_id' => isset($game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId']) ? $game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId'] : NULL,
              'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
              'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
              'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
              'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
              'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
              'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
              'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
              'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
              'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
              'time' => isset($time) ? $time : NULL,
              'value' => isset($value) ? $value : NULL,
            ]);

            $creeps_per_min_deltas_array[] = $creeps_per_min_deltas;
          }
        }

        if (isset($participant_details['timeline']['goldPerMinDeltas'])) {
          foreach ($participant_details['timeline']['goldPerMinDeltas'] as $time => $value) {
            $gold_per_min_deltas = $gold_per_min_deltas_storage->create([
              'name' => microtime(),
              'uid' => 1,
              'status' => 1,
              'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
              'account_id' => isset($game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId']) ? $game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId'] : NULL,
              'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
              'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
              'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
              'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
              'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
              'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
              'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
              'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
              'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
              'time' => isset($time) ? $time : NULL,
              'value' => isset($value) ? $value : NULL,
            ]);

            $gold_per_min_deltas_array[] = $gold_per_min_deltas;
          }
        }

        if (isset($participant_details['timeline']['csDiffPerMin'])) {
          foreach ($participant_details['timeline']['csDiffPerMin'] as $time => $value) {
            $cs_diff_per_min_deltas = $cs_diff_per_min_deltas_storage->create([
              'name' => microtime(),
              'uid' => 1,
              'status' => 1,
              'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
              'account_id' => isset($game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId']) ? $game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId'] : NULL,
              'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
              'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
              'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
              'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
              'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
              'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
              'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
              'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
              'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
              'time' => isset($time) ? $time : NULL,
              'value' => isset($value) ? $value : NULL,
            ]);

            $cs_diff_per_min_deltas_array[] = $cs_diff_per_min_deltas;
          }
        }

        if (isset($participant_details['timeline']['xpDiffPerMin'])) {
          foreach ($participant_details['timeline']['xpDiffPerMin'] as $time => $value) {
            $xp_diff_per_min_deltas = $xp_diff_per_min_deltas_storage->create([
              'name' => microtime(),
              'uid' => 1,
              'status' => 1,
              'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
              'account_id' => isset($game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId']) ? $game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId'] : NULL,
              'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
              'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
              'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
              'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
              'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
              'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
              'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
              'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
              'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
              'time' => isset($time) ? $time : NULL,
              'value' => isset($value) ? $value : NULL,
            ]);

            $xp_diff_per_min_deltas_array[] = $xp_diff_per_min_deltas;
          }
        }

        if (isset($participant_details['timeline']['damageTakenPerMinDeltas'])) {
          foreach ($participant_details['timeline']['damageTakenPerMinDeltas'] as $time => $value) {
            $damage_taken_per_min_deltas = $damage_taken_per_min_deltas_storage->create([
              'name' => microtime(),
              'uid' => 1,
              'status' => 1,
              'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
              'account_id' => isset($game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId']) ? $game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId'] : NULL,
              'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
              'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
              'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
              'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
              'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
              'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
              'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
              'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
              'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
              'time' => isset($time) ? $time : NULL,
              'value' => isset($value) ? $value : NULL,
            ]);

            $damage_taken_per_min_deltas_array[] = $damage_taken_per_min_deltas;
          }
        }

        if (isset($participant_details['timeline']['damageTakenDiffPerMinDeltas'])) {
          foreach ($participant_details['timeline']['damageTakenDiffPerMinDeltas'] as $time => $value) {
            $damage_taken_diff_per_min_deltas = $damage_taken_diff_per_min_deltas_storage->create([
              'name' => microtime(),
              'uid' => 1,
              'status' => 1,
              'game_id' => isset($game_details['gameId']) ? $game_details['gameId'] : NULL,
              'account_id' => isset($game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId']) ? $game_details['participantIdentities'][$participant_details['participantId'] - 1]['player']['accountId'] : NULL,
              'platform_id' => isset($game_details['platformId']) ? $game_details['platformId'] : NULL,
              'game_creation' => isset($game_details['gameCreation']) ? $game_details['gameCreation'] : NULL,
              'game_duration' => isset($game_details['gameDuration']) ? $game_details['gameDuration'] : NULL,
              'queue_id' => isset($game_details['queueId']) ? $game_details['queueId'] : NULL,
              'map_id' => isset($game_details['mapId']) ? $game_details['mapId'] : NULL,
              'season_id' => isset($game_details['seasonId']) ? $game_details['seasonId'] : NULL,
              'game_version' => isset($game_details['gameVersion']) ? $game_details['gameVersion'] : NULL,
              'game_mode' => isset($game_details['gameMode']) ? $game_details['gameMode'] : NULL,
              'game_type' => isset($game_details['gameType']) ? $game_details['gameType'] : NULL,
              'time' => isset($time) ? $time : NULL,
              'value' => isset($value) ? $value : NULL,
            ]);

            $damage_taken_diff_per_min_deltas_array[] = $damage_taken_diff_per_min_deltas;
          }
        }

        $participants[] = $stats;
      }

      try {
        $game->save();

        if (!empty($teams)) {
          foreach ($teams as $team) {
            $team->save();
          }
        }

        if (!empty($participants)) {
          foreach ($participants as $stats) {
            $stats->save();
          }
        }

        if (!empty($creeps_per_min_deltas_array)) {
          foreach ($creeps_per_min_deltas_array as $cpmd) {
            $cpmd->save();
          }
        }

        if (!empty($gold_per_min_deltas_array)) {
          foreach ($gold_per_min_deltas_array as $gpmd) {
            $gpmd->save();
          }
        }

        if (!empty($cs_diff_per_min_deltas_array)) {
          foreach ($cs_diff_per_min_deltas_array as $cdpmd) {
            $cdpmd->save();
          }
        }

        if (!empty($xp_diff_per_min_deltas_array)) {
          foreach ($xp_diff_per_min_deltas_array as $xdpmd) {
            $xdpmd->save();
          }
        }

        if (!empty($damage_taken_per_min_deltas_array)) {
          foreach ($damage_taken_per_min_deltas_array as $dtpmd) {
            $dtpmd->save();
          }
        }

        if (!empty($damage_taken_diff_per_min_deltas_array)) {
          foreach ($damage_taken_diff_per_min_deltas_array as $dtdpmd) {
            $dtdpmd->save();
          }
        }
      }
      catch (EntityStorageException $e) {
        $athenaUtility->setLogMessage('athena_utility', print_r($e->getMessage(), TRUE), 'error');
      }
    }
  }
}

/**
 * Updates stats cache with new data.
 *
 * @param string|int $uid
 *   The uid of the user.
 */
function batch_set_cache_stats($uid) {
  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');
  /* @var $leagueUtility \Drupal\athena_utility\AthenaLeagueUtility */ $leagueUtility = \Drupal::service('athena_utility.league_utility');
  $cache_default = $athenaUtility->getCache();
  $stats_details = $leagueUtility->getUserStatsDetails($uid);
  $puuid = $leagueUtility->getPuuid($uid);
  $cache_default->set('athena_utility_league_stats_' . $puuid, $stats_details);
}

/**
 * Updates summoner cache with new data.
 *
 * @param string|int $uid
 *   The uid of the user.
 */
function batch_set_cache_summoner($uid) {
  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');
  /* @var $leagueUtility \Drupal\athena_utility\AthenaLeagueUtility */ $leagueUtility = \Drupal::service('athena_utility.league_utility');
  $cache_default = $athenaUtility->getCache();
  $summoner_details = $leagueUtility->getUserSummonerDetails($uid);
  $puuid = $leagueUtility->getPuuid($uid);
  $cache_default->set('athena_utility_league_summoner_' . $puuid, $summoner_details);
}

/**
 * Updates a user account with latest info (based on PUUID).
 *
 * @param string|int $uid
 *   The uid of the user.
 */
function batch_update_user_account($uid) {
  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');
  /* @var $leagueUtility \Drupal\athena_utility\AthenaLeagueUtility */ $leagueUtility = \Drupal::service('athena_utility.league_utility');

  $puuid = $leagueUtility->getPuuid($uid);
  $summoner_details = $leagueUtility->getSummonerByPuuid($puuid);
  $leagueUtility->updateSummonerDetails($uid, $summoner_details);
}

/**
 * Updates given user's caching state and invalidates old tags.
 *
 * @param string|int $uid
 *   An array of uids.
 */
function batch_set_cache_finished($uid) {
  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');
  /* @var $leagueUtility \Drupal\athena_utility\AthenaLeagueUtility */ $leagueUtility = \Drupal::service('athena_utility.league_utility');

  /* @var $cache_tags_invalidator \Drupal\Core\Cache\CacheTagsInvalidator */ $cache_tags_invalidator = \Drupal::service('cache_tags.invalidator');

  // SET CACHING VARIABLE.
  $user = $athenaUtility->loadUser($uid);
  $user->set('field_athena_league_caching', FALSE);
  $cache_tags = $leagueUtility->getCacheTagsToInvalidate($uid);

  try {
    $user->save();
    $cache_tags_invalidator->invalidateTags($cache_tags);
  }
  catch (EntityStorageException $e) {
    $athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 72.', 'error');
  }
}

/**
 * Logs the batch process on completion.
 *
 * @param bool $success
 *   If the batch succeeded.
 * @param mixed $results
 *   The results.
 * @param mixed $operations
 *   The operations.
 */
function finished_batch_update_match_history($success, $results, $operations) {
  if ($success) {
    $message = t('@count batch operations successfully processed by update_match_history.batch.inc.', [
      '@count' => count($results),
    ]);
    $message_type = 'notice';
  }
  else {
    $error_operation = reset($operations);
    $message = t('An error occurred from update_match_history.batch.inc while processing %error_operation with arguments: @arguments', [
      '%error_operation' => $error_operation[0],
      '@arguments' => print_r($error_operation[1], TRUE),
    ]);
    $message_type = 'error';
  }

  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');
  $athenaUtility->setLogMessage('athena_utility', $message, $message_type);
}

function batch_set_cache_static() {
  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');
  /* @var $leagueUtility \Drupal\athena_utility\AthenaLeagueUtility */ $leagueUtility = \Drupal::service('athena_utility.league_utility');
  $cache_default = $athenaUtility->getCache();
  $static_data = $leagueUtility->getStaticData();

  if (isset($static_data['n']) && isset($static_data['cdn'])) {
    $cache_default->set('athena_utility_league_static', $static_data);
  }
}
