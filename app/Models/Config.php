<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * get config
     * @param string $key
     * @param string $default
     * @return \App\Models\Config
     */
    public static function getConfig($key, $default = '')
    {
        $config = Config::firstOrNew(['key' => $key], ['value' => $default]);
        return $config;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return json_decode($this->value, true);
    }

    /**
     * @return object
     */
    public function asObject()
    {
        return (object) $this->asArray();
    }

    /**
     * @param 
     * @return bool
     */
    public function setJson($key, $value)
    {
        $val = $this->asArray();
        $val[$key] = $value;
        $this->value = json_encode($val);
        return $this->save();
    }

    /**
     * @param 
     * @return bool
     */
    public function mergeJson($value)
    {
        $val = $this->asArray();
        $val = array_merge($val, $value);
        $this->value = json_encode($val);
        return $this->save();
    }
}
