<php>
class Post extends Model
{
    protected $fillable = ['content', 'type', 'image_url'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function songs() {
        return $this->belongsToMany(Song::class, 'post_song');
    }

    public function scopeTheories($query) {
        return $query->where('type', 'theory');
    }
}
</php>