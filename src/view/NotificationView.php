<?php


namespace ppil\view;


class NotificationView
{
    public static function renderNotificationsList($data)
    {
        $notificationsList = '';
        foreach ($data as $notification) {
            $notificationsList .= str_replace('${content}', $notification->message, file_get_contents('./html/notification-element.html'));
        }
        $template = file_get_contents('./html/account-notifications.html');

        $template = str_replace('${notification_list}', $notificationsList, $template);

        return ViewRendering::render($template, 'Mes notification');
    }
}