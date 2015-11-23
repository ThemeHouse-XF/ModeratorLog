<?php

/**
 *
 * @see XenForo_Model_Log
 */
class ThemeHouse_ModeratorLog_Extend_XenForo_Model_Log extends XFCP_ThemeHouse_ModeratorLog_Extend_XenForo_Model_Log
{

    public function logModeratorActionLocal($contentType, array $content, $action, array $actionParams = array(),
        $parentContent = null, array $logUser = null)
    {
        $xenOptions = XenForo_Application::get('options');

        if ($xenOptions->th_moderatorLog_splitMoveCopyPosts) {
            $existingActions = array(
                'post_copy_target_existing',
                'post_move_target_existing'
            );
            $actions = array_merge($existingActions, array(
                'post_copy_target',
                'post_move_target',
            ));
            if (in_array($action, $actions)) {
                $newAction = substr($action, strlen('post_'));
                /* @var $postModel XenForo_Model_Post */
                $postModel = $this->getModelFromCache('XenForo_Model_Post');

                $posts = $postModel->getPostsByIds(explode(', ', $actionParams['ids']));
                unset($actionParams['ids']);

                $logId = false;
                $i = 0;
                foreach ($posts as $postId => $post) {
                    if ($i > 0 && !in_array($action, $existingActions)) {
                        $newAction = substr($newAction, 0, strlen('move_target'));
                    }
                    $actionParams['id'] = $postId;
                    $logId = $this->logModeratorActionLocal('post', $post, $newAction, $actionParams, $content,
                        $logUser);
                    $i++;
                }

                return $logId;
            }
        }

        return parent::logModeratorActionLocal($contentType, $content, $action, $actionParams, $parentContent, $logUser);
    } /* END logModeratorActionLocal */

    public function prepareModeratorLogEntries(array $entries)
    {
        foreach ($entries AS $key => &$entry)
        {
            $entry['content_user'] = array(
            	'user_id' => $entry['content_user_id'],
                'username' => $entry['content_username']
            );
        }

        return parent::prepareModeratorLogEntries($entries);
    } /* END prepareModeratorLogEntries */

    public function prepareModeratorLogEntry(array $entry)
    {
        $entry['content_user'] = array(
            'user_id' => $entry['content_user_id'],
            'username' => $entry['content_username']
        );

        return parent::prepareModeratorLogEntry($entry);
    } /* END prepareModeratorLogEntry */
}