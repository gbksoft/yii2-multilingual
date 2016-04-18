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
