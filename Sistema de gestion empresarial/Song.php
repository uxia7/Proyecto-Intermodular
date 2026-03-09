<php>
class Song extends Model
{
    protected $fillable = ['title', 'artist', 'group_id', 'external_url'];

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function posts() {
        return $this->belongsToMany(Post::class, 'post_song');
    }
}
</php>
