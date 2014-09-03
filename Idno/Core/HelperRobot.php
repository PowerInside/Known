<?php

    namespace Idno\Core {

        class HelperRobot extends \Idno\Common\Component
        {

            function init()
            {
                if (site()->session()->isLoggedOn()) {
                    if (!empty(site()->session()->currentUser()->robot_state)) {
                        $this->registerEvents();
                    }
                }
            }

            function registerEvents()
            {

                \Idno\Core\site()->addEventHook('syndicate', function (\Idno\Core\Event $event) {

                    if ($object = $event->data()['object']) {
                        if (site()->session()->isLoggedOn()) {
                            if (!empty(site()->session()->currentUser()->robot_state)) {
                                $user = site()->session()->currentUser();
                                switch ($user->robot_state) {

                                    case '1':
                                        if (class_exists('IdnoPlugins\Status') && $object instanceof \IdnoPlugins\Status) {
                                            $user->robot_state = '2a';
                                        } else {
                                            $user->robot_state = '2b';
                                        }
                                        break;
                                    case '2a':
                                        if (class_exists('IdnoPlugins\Photo') && $object instanceof \IdnoPlugins\Photo) {
                                            $user->robot_state = '3a';
                                        }
                                        break;
                                    case '2b':
                                        $user->robot_state = '3b';
                                        break;

                                }
                                $user->save();
                                site()->session()->refreshSessionUser($user);

                            }
                        }
                    }

                });

            }

        }

    }