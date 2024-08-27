<?php

/***************************************************************************
 *
 *    ougc Fill Profile Field With Post Count task (/inc/tasks/fillProfileFieldWithPostCount.php)
 *    Author: Omar Gonzalez
 *    Copyright: © 2023 Omar Gonzalez
 *
 *    Website: https://ougc.network
 *
 *    Fill one or more custom profile fields with the user post count value.
 *
 ***************************************************************************
 ****************************************************************************
 * This program is protected software: you can make use of it under
 * the terms of the OUGC Network EULA as detailed by the included
 * "EULA.TXT" file.
 *
 * This program is distributed with the expectation that it will be
 * useful, but WITH LIMITED WARRANTY; with a limited warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * OUGC Network EULA included in the "EULA.TXT" file for more details.
 *
 * You should have received a copy of the OUGC Network EULA along with
 * the package which includes this file.  If not, see
 * <https://ougc.network/eula.txt>.
 ****************************************************************************/

declare(strict_types=1);

use const ougc\Tasks\FillProfileFieldWithPostCount\SETTINGS;

define('ougc\Tasks\FillProfileFieldWithPostCount\SETTINGS', [
    'profileFields' => [
        // where 0 is the custom profile field identifier (fid)
        0 => [
            'forumIDs' => [1, 2],// forum identifiers (fid)
            'visible' => [1], // 1 for visible posts
            'queryLimit' => 50
        ],
        0 => [
            'forumIDs' => -1, // all
            'visible' => [-1, 0, 1], // 0 for unapproved, -1 for soft deleted
            'queryLimit' => 50
        ]
    ]
]);

function task_ougcSubscriptions(array &$taskData)
{
    global $mybb, $db;

    $taskCacheData = $mybb->cache->read('ougcFillProfileFieldWithPostCount');

    $profileFieldsCacheData = $mybb->cache->read('profilefields');

    $taskSettings = SETTINGS;

    foreach ($taskSettings['profileFields'] as $profileFieldID => $fieldSettings) {
        if (empty($profileFieldID) || empty($profileFieldsCacheData[$profileFieldID])) {
            continue;
        }

        if (!isset($taskCacheData[$profileFieldID])) {
            $taskCacheData[$profileFieldID] = [];
        }

        $cacheData = &$taskCacheData[$profileFieldID];

        if (!isset($cacheData['currentUserCount'])) {
            $cacheData['currentUserCount'] = 0;
        }

        $whereClauses = [];

        if (isset($fieldSettings['forumIDs']) && $fieldSettings['forumIDs'] !== -1) {
            $forumIDs = implode("','", $fieldSettings['forumIDs']);

            $whereClauses[] = "t.fid IN ('{$forumIDs}')";
        }

        if (isset($fieldSettings['visible'])) {
            $visibleStates = implode("','", $fieldSettings['visible']);

            $whereClauses[] = "t.visible IN ('{$visibleStates}')";

            $whereClauses[] = "p.visible IN ('{$visibleStates}')";
        }

        $dbQuery = $db->simple_select(
            "users u LEFT JOIN {$db->table_prefix}posts p ON (p.uid=u.uid) LEFT JOIN {$db->table_prefix}threads t ON (t.tid=p.tid)",
            'COUNT(u.uid) as totalUsers',
            implode(' AND ', $whereClauses),
        );

        $cacheData['totalUsers'] = $db->fetch_field($dbQuery, 'totalUsers');

        $dbQuery = $db->simple_select(
            "users u LEFT JOIN {$db->table_prefix}posts p ON (p.uid=u.uid) LEFT JOIN {$db->table_prefix}threads t ON (t.tid=p.tid)",
            'u.uid, COUNT(p.pid) as totalPosts',
            implode(' AND ', $whereClauses),
            [
                'limit' => $fieldSettings['queryLimit'] ?? 50,
                'limit_start' => $cacheData['currentUserCount'],
                'group_by' => 'u.uid'
            ]
        );

        while ($rowData = $db->fetch_array($dbQuery)) {
            $userID = $rowData['uid'];

            $db->update_query(
                'userfields',
                ["fid{$profileFieldID}" => $rowData['totalPosts']],
                "ufid='{$userID}'"
            );

            ++$cacheData['currentUserCount'];
        }

        if ($cacheData['currentUserCount'] >= $cacheData['totalUsers']) {
            $cacheData['currentUserCount'] = 0;
        }
    }

    $mybb->cache->cache('ougcFillProfileFieldWithPostCount', $taskCacheData);

    add_task_log($taskData, 'The Fill Profile Field With Post Count task ran successfully.');
}