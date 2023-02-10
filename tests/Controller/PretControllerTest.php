<?php

namespace App\Test\Controller;

use App\Entity\Pret;
use App\Repository\PretRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PretControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PretRepository $repository;
    private string $path = '/pret/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Pret::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Pret index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'pret[user_name]' => 'Testing',
            'pret[user_mail]' => 'Testing',
            'pret[date_pret]' => 'Testing',
            'pret[date_rendu]' => 'Testing',
            'pret[status]' => 'Testing',
            'pret[materiel]' => 'Testing',
        ]);

        self::assertResponseRedirects('/pret/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Pret();
        $fixture->setUser_name('My Title');
        $fixture->setUser_mail('My Title');
        $fixture->setDate_pret('My Title');
        $fixture->setDate_rendu('My Title');
        $fixture->setStatus('My Title');
        $fixture->setMateriel('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Pret');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Pret();
        $fixture->setUser_name('My Title');
        $fixture->setUser_mail('My Title');
        $fixture->setDate_pret('My Title');
        $fixture->setDate_rendu('My Title');
        $fixture->setStatus('My Title');
        $fixture->setMateriel('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'pret[user_name]' => 'Something New',
            'pret[user_mail]' => 'Something New',
            'pret[date_pret]' => 'Something New',
            'pret[date_rendu]' => 'Something New',
            'pret[status]' => 'Something New',
            'pret[materiel]' => 'Something New',
        ]);

        self::assertResponseRedirects('/pret/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getUser_name());
        self::assertSame('Something New', $fixture[0]->getUser_mail());
        self::assertSame('Something New', $fixture[0]->getDate_pret());
        self::assertSame('Something New', $fixture[0]->getDate_rendu());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getMateriel());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Pret();
        $fixture->setUser_name('My Title');
        $fixture->setUser_mail('My Title');
        $fixture->setDate_pret('My Title');
        $fixture->setDate_rendu('My Title');
        $fixture->setStatus('My Title');
        $fixture->setMateriel('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/pret/');
    }
}
