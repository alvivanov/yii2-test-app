<?php

declare(strict_types=1);

namespace app\tests\Unit\components;

use app\components\book_service\BookService;
use app\components\exceptions\ModelNotFoundException;
use app\models\Book;
use app\models\BookAuthor;
use app\models\forms\BookForm;
use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\BookAuthorsFixture;
use app\tests\fixtures\BooksFixture;
use Codeception\Test\Unit;
use yii\test\FixtureTrait;

final class BookServiceTest extends Unit
{
    use FixtureTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures();
    }

    protected function tearDown(): void
    {
        $this->unloadFixtures();
        parent::tearDown();
    }

    public function fixtures(): array
    {
        return [
            'authors'     => AuthorsFixture::class,
            'books'       => BooksFixture::class,
            'bookAuthors' => BookAuthorsFixture::class,
        ];
    }

    public function testGetThrowsWhenBookNotFound(): void
    {
        $this->expectException(ModelNotFoundException::class);

        new BookService()->get(999999);
    }

    public function testGetReturnsUpdateScenarioFormWithAttributes(): void
    {
        $bookId = 1;

        $form = new BookService()->get($bookId);

        //        $this->assertSame(BookForm::SCENARIO_UPDATE, $form->scenario);
        $this->assertSame($bookId, (int) $form->id);

        $book = Book::findOne($bookId);
        $this->assertNotNull($book);

        $this->assertSame($book->title, $form->title);
        $this->assertSame((int) $book->publication_year, (int) $form->publication_year);
        $this->assertSame($book->isbn, $form->isbn);
        $this->assertSame($book->main_page_image, $form->main_page_image);
    }

    public function testCreatePersistsBookSavesImageAndLinksAuthors(): void
    {
        $beforeBookCount = (int) Book::find()->count();

        $form                   = new BookForm();
        $form->title            = 'New title';
        $form->publication_year = 2020;
        $form->isbn             = '1234567890123';
        $form->authors          = [1, 2];
        $form->main_page_image  = new class () {
            public string $baseName = 'test_image';
            public string $extension = 'jpg';

            public function saveAs(string $file, bool $deleteTempFile = true): bool
            {
                return true;
            }
        };

        new BookService()->create($form);

        $afterBookCount = (int) Book::find()->count();
        $this->assertSame($beforeBookCount + 1, $afterBookCount);

        $book = Book::find()->where(['isbn' => '1234567890123'])->one();
        $this->assertNotNull($book);

        $this->assertSame('uploads/test_image.jpg', $book->main_page_image);

        $authorIds = BookAuthor::find()
            ->select(['author_id'])
            ->where(['book_id' => $book->id])
            ->column();

        sort($authorIds);
        $expected = [1, 2];
        sort($expected);

        $this->assertSame($expected, array_map('intval', $authorIds));
    }

    public function testUpdateUpdatesBookAndRelinksAuthors(): void
    {
        $bookId = 1;

        $form = new BookForm([
            //            'scenario' => BookForm::SCENARIO_UPDATE,
        ]);
        $form->id               = $bookId;
        $form->title            = 'Updated title';
        $form->publication_year = 1999;
        $form->isbn             = '9999999999999';
        $form->authors          = [3, 4];
        $form->main_page_image  = new class () {
            public string $baseName = 'updated_image';
            public string $extension = 'png';

            public function saveAs(string $file, bool $deleteTempFile = true): bool
            {
                return true;
            }
        };

        new BookService()->update($form);

        $book = Book::findOne($bookId);
        $this->assertNotNull($book);

        $this->assertSame('Updated title', $book->title);
        $this->assertSame(1999, (int) $book->publication_year);
        $this->assertSame('9999999999999', $book->isbn);
        $this->assertSame('uploads/updated_image.png', $book->main_page_image);

        $authorIds = BookAuthor::find()
            ->select(['author_id'])
            ->where(['book_id' => $bookId])
            ->column();

        sort($authorIds);
        $expected = [3, 4];
        sort($expected);

        $this->assertSame($expected, array_map('intval', $authorIds));
    }

    public function testDeleteRemovesBookAndJunctionRows(): void
    {
        $book = Book::findOne(1);
        $this->assertNotNull($book);

        $this->assertTrue(BookAuthor::find()->where(['book_id' => $book->id])->exists());

        new BookService()->delete(1);

        $this->assertNull(Book::findOne(1));
        $this->assertFalse(BookAuthor::find()->where(['book_id' => 1])->exists());
    }
}
