<?php
<<<<<<< HEAD

/**
 * Contrexx
 *
 * @link      http://www.contrexx.com
 * @copyright Comvation AG 2007-2014
 * @version   Contrexx 4.0
 * 
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Contrexx" is a registered trademark of Comvation AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

=======
>>>>>>> f7ee35166c3ea0314d3113cfac8fc8894c4d0211
include_once(ASCMS_TEST_PATH.'/testCases/DoctrineTestCase.php');

use Cx\Core\Routing\Resolver as Resolver;
use Cx\Core\Routing\Url as Url;

class ResolverTest extends DoctrineTestCase
{
    protected $mockFallbackLanguages = array(
        1 => 2,
        2 => 3
    );
    protected function insertFixtures() {
        $repo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');

        $root = new \Cx\Core\ContentManager\Model\Entity\Node();
        
        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n2 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n3 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n4 = new \Cx\Core\ContentManager\Model\Entity\Node(); //redirection
        $n5 = new \Cx\Core\ContentManager\Model\Entity\Node(); //alias

        $n1->setParent($root);
        $n2->setParent($n1);
        $n3->setParent($n2);
        $n4->setParent($root);
        $n5->setParent($root);

        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(1);
        $p1->setTitle('testpage1');
        $p1->setNode($n1);

        $p4 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p4->setLang(1);
        $p4->setTitle('testpage1_child');
        $p4->setNode($n2);

        $p5 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p5->setLang(1);
        $p5->setTitle('subtreeTest_target');
        $p5->setNode($n3);
        
        $p6 = new \Cx\Core\ContentManager\Model\Entity\Page();
        $p6->setLang(0);
        $p6->setTitle('testalias');
        $p6->setNode($n5);
        $p6->setType(\Cx\Core\ContentManager\Model\Entity\Page::TYPE_ALIAS);
        $p6->setTarget($p4->getId().'|1');

        self::$em->persist($root);
        self::$em->persist($n1);
        self::$em->persist($n2);
        self::$em->persist($n3);
        self::$em->persist($n4);
        
        self::$em->persist($p1);
        self::$em->persist($p4);
        self::$em->persist($p5);
        self::$em->persist($p6);

        self::$em->flush();

        $p2 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p2->setLang(1);
        $p2->setType(\Cx\Core\ContentManager\Model\Entity\Page::TYPE_REDIRECT);
        $p2->setTitle('redirection');
        $p2->setNode($n4);
        $p2->setTarget($n2->getId().'|?foo=test');

        self::$em->persist($p2);

        self::$em->flush();

        //make sure we re-fetch a correct state
        self::$em->clear();
    }

    public function testTargetPathAndParams() {
        $this->insertFixtures();

        $lang = 1;

        $url = new Url('http://example.com/testpage1/testpage1_child/?foo=test');
        $resolver = new Resolver($url, $lang, self::$em, '', $this->mockFallbackLanguages);
        $resolver->resolve();

        $this->assertEquals('testpage1/testpage1_child/', $url->getTargetPath());
        $this->assertEquals('?foo=test', $url->getParams());

        $this->assertEquals(true, $url->isRouted());
    }

    public function testFoundPage() {
        $this->insertFixtures();

        $lang = 1;

        $url = new Url('http://example.com/testpage1/testpage1_child/?foo=test');
        $resolver = new Resolver($url, $lang, self::$em, '', $this->mockFallbackLanguages);
        $resolver->resolve();

        $page = $resolver->getPage();
        $this->assertEquals('testpage1_child', $page->getTitle());
    }

    /**
     * @expectedException Cx\Core\Routing\ResolverException
     */
    public function testInexistantPage() {
        $this->insertFixtures();

        $lang = 1;

        $url = new Url('http://example.com/inexistantPage/?foo=test');
        $resolver = new Resolver($url, $lang, self::$em, '', $this->mockFallbackLanguages);
        $resolver->resolve();

        $page = $resolver->getPage();
    }

    public function testRedirection() {
        $this->insertFixtures();

        $lang = 1;

        $url = new Url('http://example.com/redirection/');
        $resolver = new Resolver($url, $lang, self::$em, '', $this->mockFallbackLanguages, true);
        $resolver->resolve();

        $page = $resolver->getPage();
        $this->assertEquals('testpage1_child', $page->getTitle());
    }

    protected function getResolvedFallbackPage() {
        $repo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');

        $root = new \Cx\Core\ContentManager\Model\Entity\Node();
        
        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n2 = new \Cx\Core\ContentManager\Model\Entity\Node();

        $n1->setParent($root);

        //test if requesting this page...
        $p2 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p2->setLang(1);
        $p2->setTitle('pageThatsFallingBack');
        $p2->setNode($n1);
        $p2->setType(\Cx\Core\ContentManager\Model\Entity\Page::TYPE_FALLBACK);

        //... will yield contents of this page as result.
        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(2);
        $p1->setTitle('pageThatHoldsTheContent');
        $p1->setNode($n1);
        $p1->setType('content');
        $p1->setContent('fallbackContent');

        self::$em->persist($root);
        self::$em->persist($n1);
        self::$em->persist($n2);
        self::$em->persist($p1);
        self::$em->persist($p2);
        self::$em->flush();
        self::$em->clear();

        $url = new Url('http://example.com/pageThatsFallingBack/');
        $resolver = new Resolver($url, 1, self::$em, '', $this->mockFallbackLanguages, true);
        $resolver->resolve();
        $p = $resolver->getPage();

        return $p;
    }

    public function testFallbackRedirection() {
        $p = $this->getResolvedFallbackPage();

        $this->assertEquals('fallbackContent', $p->getContent());
        $this->assertEquals(true, $p->hasFallbackContent());
    }

    /**
     * @expectedException Cx\Model\Events\PageEventListenerException
     */
    public function testPageListenerForResolvedPages() {
        $p = $this->getResolvedFallbackPage();

        //try to change something
        $p->setContent('asdf');
        self::$em->persist($p);
        self::$em->flush();
    }
    
    public function testAliasResolving() {
        $this->insertFixtures();
        
        $url = new Url('http://example.com/testalias');
        $resolver = new Resolver($url, 1, self::$em, '', $this->mockFallbackLanguages, true);
        $resolver->resolveAlias();
        $resolver->resolve();
        $p = $resolver->getPage();
        
        $this->assertEquals(1, $p->getLang());
        $this->assertEquals('testpage1_child', $p->getTitle());
    }
}
