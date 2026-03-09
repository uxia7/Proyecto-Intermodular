<php>
class User extends Authenticatable
{
    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function following() {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function biasGroup() {
        return $this->belongsTo(Group::class, 'bias_group_id');
    }
}
</php>