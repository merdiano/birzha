<?php namespace RainLab\Notify\Models;

use Guzzle\Http\Url;
use Model;
use Markdown;
use TPS\Birzha\Events\MessageReceivedEvent;
use TPS\Birzha\Events\PaymentReviewedEvent;
use TPS\Birzha\Events\ProductReviewedEvent;

/**
 * Notification Model stored in the database
 */
class Notification extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rainlab_notify_notifications';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array List of attribute names which are json encoded and decoded from the database.
     */
    protected $jsonable = ['data'];

    /**
     * @var array List of datetime attributes to convert to an instance of Carbon/DateTime objects.
     */
    protected $dates = ['read_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['parsed_body'];

    /**
     * @var array Relations
     */
    public $morphTo = [
        'notifiable' => [],
    ];

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function unread()
    {
        return $this->read_at === null;
    }

    /**
     * Get the entity's unread notifications.
     */
    public function scopeApplyUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Get the entity's read notifications.
     */
    public function scopeApplyRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Get the parsed body of the announcement.
     *
     * @return string
     */
    public function getParsedBodyAttribute()
    {
        return Markdown::parse($this->body);
    }

    /**
     * Get the description of the notification
     * 
     * @return string
     */
    public function getDescriptionAttribute()
    {
        $e = new $this->event_type;
    
        if($e instanceof MessageReceivedEvent) {

            return trans('validation.new_message');

        } elseif($e instanceof ProductReviewedEvent) {

            return trans('validation.product_reviewed');

        } elseif($e instanceof PaymentReviewedEvent) {

            return trans('validation.payment_reviewed');
        }

        return 'Unknown type notification';
    }

    /**
     * Get the link where to redirect when the notification is clicked
     * 
     * @return string
     */
    public function getLinkAttribute()
    {
        $e = new $this->event_type;
    
        if($e instanceof MessageReceivedEvent) {

            return \Url::to('/messages');

        } elseif($e instanceof ProductReviewedEvent) {

            return \Url::to('/my-posts');

        } elseif($e instanceof PaymentReviewedEvent) {

            return \Url::to('/balance');
        }

        return \Url::to('/');
    }
}
