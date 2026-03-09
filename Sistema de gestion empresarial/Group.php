<php>
    class Group extends Model
    {
    protected $fillable = ['name', 'fandom_name', 'logo_url'];

    public function songs() {
    return $this->hasMany(Song::class);
    }

    public function biasedUsers() {
    return $this->hasMany(User::class, 'bias_group_id');
    }
    }

</php>