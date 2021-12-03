function toggleNotificationsPopover(el) {
    
    if($(el).next().hasClass('notification_area')) {
        $(el).request('onLoadNotifications', {
            update: { '@notifications-list': '#notification_area' }
        })
    } else if($(el).next().hasClass('accord_notification')) {
        $(el).request('onLoadNotifications', {
            update: { '@notifications-list': '#accord_notification' }
        })
    }
    
}
