Multilingual Module for Yii 2
========================

Start
------------

Скачать [composer](http://getcomposer.org/download/).

После запустить

```
php composer.phar require --prefer-dist gbksoft/yii2-multilingual
```

или добавить в composer.json

```json
"gbksoft/yii2-multilingual": "*"
```

DB

### Languages table

```bash
php yii migrate --migrationPath=/path/to/in/yours/project/vendor/gbksoft/yii2-multilingual/src/migrations
```

### Post table
```sql
CREATE TABLE `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB

```

### Post translations table
```sql
CREATE TABLE `post_translation` (
  `post_id` int(10) unsigned NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `text` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`post_id`,`language_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `fk_post_translation_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_translation_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB

```

### Подключение модуля для редактирования/создания языков

```php
// config.php
return [
    'modules' => [
        'swagger' => [
            'class' => SwaggerModule::class,
        ],
        'rbac' => [
            'class' => RbacModule::class,
            'layout' => 'left-menu',
            'mainLayout' => '@app/views/layouts/main.php',
        ],
        'language' => [
            'class' => \gbksoft\multilingual\Module::class,
        ],
    ]
];
```

### Подключение в entity модель

```php
class Post extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ml' => [
                'class' => Multilingual::class,
                'translationModelName' => PostTranslation::class,
                'translationOwnerField' => 'post_id',
                'languageField' => 'language_id',
            ],
        ];
    }
}
```

### Модель переводов

```php
/**
 * Class PostTranslation
 *
 * @property integer $post_id
 * @property integer $language_id
 * @property string $name
 * @property string $text
 */
class PostTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'text', 'post_id', 'language_id'], 'required'],
            [['post_id', 'language_id'], 'unique', 'targetAttribute' => ['post_id', 'language_id']],
            [['name', 'text'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost() {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage() {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }
}
```

### Использование в котроллере

```php
/* in post array
 [
    'Post' => [ << model form name ($post->formName())
        'en-EN' => [
            'name' => '999999',
            'text' => '9999997777'
        ],
        'ru-RU' => [
            'name' => '777777',
            'text' => '8888888'
        ],
    ]
]
 */
$post->saveTranslations(\Yii::$app->request->post());

$post->getTranslation('en-EN')->one(); // en language
$post->getTranslation()->one(); // default language (Yii::$app->language)
$post->getTranslations()->all(); // all languages
```
