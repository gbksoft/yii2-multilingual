<?php
/**
 * Copyright © 2016 GBKSOFT. Web and Mobile Software Development.
 * See LICENSE.txt for license details.
 */
namespace  tests\codeception\backend\unit\components;

use Codeception\TestCase\Test;
use gbksoft\multilingual\components\LanguageQueryFactory;
use gbksoft\multilingual\components\UrlManager;
use gbksoft\multilingual\models\Language;
use gbksoft\multilingual\models\queries\LanguageQuery;

/**
 * Class UrlManagerTest
 *
 * @see \gbksoft\multilingual\components\UrlManager
 */
class UrlManagerTest extends Test
{
    /**
     * @var LanguageQueryFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $queryFactory;

    /**
     * @var LanguageQuery|\PHPUnit_Framework_MockObject_MockObject
     */
    private $queryMock;

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        $this->queryFactory = $this->getMockBuilder(LanguageQueryFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder(LanguageQuery::class)
            ->setMethods(['getByUrl', 'one', 'getDefault'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryFactory->expects(self::any())
            ->method('create')
            ->willReturn($this->queryMock);
    }

    /**
     * Run test for createUrl method
     *
     * @param string $language
     * @param string $expected
     * @param array $input
     *
     * @dataProvider adataProviderForTestCreateUrl
     */
    public function testCreateUrl($language, $expected, array $input)
    {
        $this->queryMock->expects(self::once())
            ->method('getByUrl')
            ->willReturnSelf();

        $this->queryMock->expects(self::once())
            ->method('one')
            ->willReturn($this->createLanguageMock($language));

        $input['language'] = $language;

        self::assertEquals($expected, $this->createManagerMock()->createUrl($input));
    }

    /**
     * Run test for createUrl method (default language)
     */
    public function testCreateUrlByDefaultLanguage()
    {
        $language = 'en';

        $this->queryMock->expects(self::at(0))
            ->method('getByUrl')
            ->willReturnSelf();

        $this->queryMock->expects(self::at(1))
            ->method('one')
            ->willReturn(null);

        $this->queryMock->expects(self::at(2))
            ->method('getDefault')
            ->willReturnSelf();

        $this->queryMock->expects(self::at(3))
            ->method('one')
            ->willReturn($this->createLanguageMock($language));

        self::assertEquals(
            '/' . $language . '/test/test',
            $this->createManagerMock()->createUrl(['/test/test'])
        );
    }

    /**
     * @return UrlManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createManagerMock()
    {
        $manager = $this->getMockBuilder(UrlManager::class)
            ->setConstructorArgs([$this->queryFactory])
            ->setMethods(['getBaseUrl'])
            ->getMock();
        $manager->showScriptName = false;
        $manager->enablePrettyUrl = true;

        $manager->expects(self::once())
            ->method('getBaseUrl')
            ->willReturn('');

        return $manager;
    }

    /**
     * @param string $language
     * @return Language|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createLanguageMock($language)
    {
        /** @var Language|\PHPUnit_Framework_MockObject_MockObject $languageMock */
        $languageMock = $this->getMockBuilder(Language::class)
            ->setMethods(['attributes'])
            ->disableOriginalConstructor()
            ->getMock();

        $languageMock->expects(self::any())
            ->method('attributes')
            ->willReturn(['url']);

        $languageMock->setAttribute('url', $language);

        return $languageMock;
    }

    /**
     * @return array
     */
    public function adataProviderForTestCreateUrl()
    {
        return [
            [
                'language' => 'ru',
                'expected' => '/ru/test/test',
                'input' => ['/test/test/'],
            ],
            [
                'language' => 'ru',
                'expected' => '/ru/test/test',
                'input' => ['test/test'],
            ],
            [
                'language' => 'ru',
                'expected' => '/ru/test/test',
                'input' => ['/test/test'],
            ]
        ];
    }
}
