<?php

declare(strict_types=1);

namespace app\tests\Unit\components;

use app\components\author_service\AuthorService;
use app\components\exceptions\ModelNotFoundException;
use app\models\Author;
use app\models\AuthorSubscription;
use app\models\forms\AuthorForm;
use app\models\forms\NewBookSubscriptionForm;
use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\AuthorSubscriptionsFixture;
use app\tests\fixtures\BookAuthorsFixture;
use app\tests\fixtures\BooksFixture;
use Codeception\Test\Unit;
use yii\test\FixtureTrait;

final class AuthorServiceTest extends Unit
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
            'authors'             => AuthorsFixture::class,
            'authorSubscriptions' => AuthorSubscriptionsFixture::class,
            'books'               => BooksFixture::class,
            'bookAuthors'         => BookAuthorsFixture::class,
        ];
    }

    public function testGetThrowsWhenAuthorNotFound(): void
    {
        $this->expectException(ModelNotFoundException::class);

        new AuthorService()->get(999999);
    }

    public function testCreatePersistsNewAuthor(): void
    {
        $beforeCount = (int) Author::find()->count();

        $form              = new AuthorForm();
        $form->first_name  = 'John';
        $form->last_name   = 'Doe';
        $form->middle_name = 'M';

        new AuthorService()->create($form);

        $afterCount = (int) Author::find()->count();
        $this->assertSame($beforeCount + 1, $afterCount);

        $this->assertTrue(
            Author::find()->where([
                'first_name'  => 'John',
                'last_name'   => 'Doe',
                'middle_name' => 'M',
            ])->exists()
        );
    }

    public function testUpdateChangesExistingAuthor(): void
    {
        $authorId = 3; // from fixtures: should exist and not be linked to books

        $form              = new AuthorForm();
        $form->first_name  = 'UpdatedFirst';
        $form->last_name   = 'UpdatedLast';
        $form->middle_name = 'UpdatedMiddle';

        new AuthorService()->update($authorId, $form);

        $author = Author::findOne($authorId);
        $this->assertNotNull($author);
        $this->assertSame('UpdatedFirst', $author->first_name);
        $this->assertSame('UpdatedLast', $author->last_name);
        $this->assertSame('UpdatedMiddle', $author->middle_name);
    }

    public function testCreateSubscriptionForNewBooksLinksSubscriptionToAuthor(): void
    {
        $authorId = 3; // author without subscription in fixture set

        $form        = new NewBookSubscriptionForm();
        $form->phone = '+79001234567';

        new AuthorService()->createSubscriptionForNewBooks($authorId, $form);

        $this->assertTrue(
            AuthorSubscription::find()->where([
                'author_id' => $authorId,
                'phone'     => '+79001234567',
            ])->exists()
        );
    }

    public function testDeleteThrowsDomainExceptionIfAuthorHasBooks(): void
    {
        $authorId = 2; // from fixtures: linked to books via book_author.php

        $this->expectException(\DomainException::class);

        new AuthorService()->delete($authorId);
    }

    //    public function testDeleteRemovesAuthorAndSubscriptions(): void
    //    {
    //        // create a subscription for author 3 first
    //        $authorId = 3;
    //
    //        $subForm        = new NewBookSubscriptionForm();
    //        $subForm->phone = '+79998887766';
    //        new AuthorService()->createSubscriptionForNewBooks($authorId, $subForm);
    //
    //        $this->assertNotNull(Author::findOne($authorId));
    //        $this->assertTrue(AuthorSubscription::find()->where(['author_id' => $authorId])->exists());
    //
    //        new AuthorService()->delete($authorId);
    //
    //        $this->assertNull(Author::findOne($authorId));
    //        $this->assertFalse(AuthorSubscription::find()->where(['author_id' => $authorId])->exists());
    //    }
}
