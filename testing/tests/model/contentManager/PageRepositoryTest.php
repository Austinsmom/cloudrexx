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
use Doctrine\Common\Util\Debug as DoctrineDebug;

include_once(ASCMS_TEST_PATH.'/testCases/DoctrineTestCase.php');

class PageRepositoryTest extends DoctrineTestCase
{
    public function testTreeByTitle() {
        $repo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');
        
        $root = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n2 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n3 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n4 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n5 = new \Cx\Core\ContentManager\Model\Entity\Node();

        $n1->setParent($root);
        $n2->setParent($n1);
        $n3->setParent($root);
        $n4->setParent($n3);
        $n5->setParent($n3);        

        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(1);
        $p1->setTitle('rootTitle_1');
        $p1->setNode($n1);

        $p2 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p2->setLang(2);
        $p2->setTitle('rootTitle_1');
        $p2->setNode($n1);

        $p3 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p3->setLang(3);
        $p3->setTitle('rootTitle_2');
        $p3->setNode($n1);

        $p4 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p4->setLang(3);
        $p4->setTitle('childTitle');
        $p4->setNode($n2);

        $p5 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p5->setLang(1);
        $p5->setTitle('otherRootChild');
        $p5->setNode($n3);

        $p6 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p6->setLang(1);
        $p6->setTitle('partialFetchTarget1');
        $p6->setNode($n4);

        $p7 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p7->setLang(1);
        $p7->setTitle('partialFetchTarget2');
        $p7->setNode($n5);

        self::$em->persist($root);

        self::$em->persist($n1);
        self::$em->persist($n2);
        self::$em->persist($n3);
        self::$em->persist($n4);
        self::$em->persist($n5);

        self::$em->persist($p1);
        self::$em->persist($p2);
        self::$em->persist($p3);
        self::$em->persist($p4);
        self::$em->persist($p5);
        self::$em->persist($p6);
        self::$em->persist($p7);

        self::$em->flush();

        //make sure we re-fetch a correct state
        self::$em->clear();

        $tree = $repo->getTreeByTitle();

        //check node assigning
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Node', $tree['rootTitle_1'][$repo::DataProperty]['node']);

        //check lang assigning
        $this->assertEquals(2, count($tree['rootTitle_1'][$repo::DataProperty]['lang']));
        $this->assertEquals(1, count($tree['rootTitle_2'][$repo::DataProperty]['lang']));

        //check children
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Node', $tree['rootTitle_2']['childTitle'][$repo::DataProperty]['node']);

        //check child of second node attached to root (special case in algorithm)
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Node', $tree['otherRootChild'][$repo::DataProperty]['node']);

        //check partial fetching of tree
        $myRoot = $repo->findOneBy(array('title' => 'otherRootChild'));
        $tree = $repo->getTreeByTitle($myRoot->getNode());

        $this->assertArrayHasKey('partialFetchTarget1', $tree);
        $this->assertArrayHasKey('partialFetchTarget2', $tree);
    }

    public function testPagesAtPath() {
        $repo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');

        $root = new \Cx\Core\ContentManager\Model\Entity\Node();

        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node(); 
        $n2 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n3 = new \Cx\Core\ContentManager\Model\Entity\Node();

        $n1->setParent($root);
        $n2->setParent($n1);
        $n3->setParent($root);

        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(1);
        $p1->setTitle('rootTitle_1');
        $p1->setNode($n1);

        $p2 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p2->setLang(2);
        $p2->setTitle('rootTitle_1');
        $p2->setNode($n1);

        $p3 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p3->setLang(3);
        $p3->setTitle('rootTitle_2');
        $p3->setNode($n1);

        $p4 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p4->setLang(3);
        $p4->setTitle('childTitle');
        $p4->setNode($n2);

        $p5 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p5->setLang(1);
        $p5->setTitle('otherRootChild');
        $p5->setNode($n3);


        self::$em->persist($root);
        self::$em->persist($n1);
        self::$em->persist($n2);
        self::$em->persist($n3);

        self::$em->persist($p1);
        self::$em->persist($p2);
        self::$em->persist($p3);
        self::$em->persist($p4);
        self::$em->persist($p5);

        self::$em->flush();

        //make sure we re-fetch a correct state
        self::$em->clear();

        //1 level
        $match = $repo->getPagesAtPath('rootTitle_1');
        $this->assertEquals('rootTitle_1',$match['matchedPath']);
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page',$match['pages'][1]);
        $this->assertEquals(array(1,2),$match['lang']);

        //2 levels
        $match = $repo->getPagesAtPath('rootTitle_2/childTitle');
        $this->assertEquals('rootTitle_2/childTitle',$match['matchedPath']);
        $this->assertEquals('',$match['unmatchedPath']);
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page',$match['pages'][3]);
        $this->assertEquals(array(3),$match['lang']);

        //3 levels, 2 in tree
        $match = $repo->getPagesAtPath('rootTitle_2/childTitle/asdfasdf');        
        $this->assertEquals('rootTitle_2/childTitle/',$match['matchedPath']);
        // check unmatched path too
        $this->assertEquals('asdfasdf',$match['unmatchedPath']);
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page',$match['pages'][3]);
        $this->assertEquals(array(3),$match['lang']);

        //3 levels, wrong lang from 2nd level
        $match = $repo->getPagesAtPath('rootTitle_1/childTitle/asdfasdf');        
        $this->assertEquals('rootTitle_1/',$match['matchedPath']);
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page',$match['pages'][1]);
        $this->assertEquals(array(1,2),$match['lang']);

        //inexistant
        $match = $repo->getPagesAtPath('doesNotExist');        
        $this->assertEquals(null,$match);

        //exact matching
        $match = $repo->getPagesAtPath('rootTitle_2/childTitle/asdfasdf', null, null, true);
        $this->assertEquals(null,$match);

        //given lang matching
        $match = $repo->getPagesAtPath('rootTitle_1', null, 1);
        $this->assertEquals('rootTitle_1',$match['matchedPath']);
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page',$match['page']);

        //second other child of root node
        $match = $repo->getPagesAtPath('otherRootChild', null, 1);
        $this->assertEquals('otherRootChild',$match['matchedPath']);        
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page',$match['page']);
    }

    public function testGetFromModuleCmdByLang() {
        $repo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');
        
        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node();

        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(1);
        $p1->setTitle('rootTitle_1');
        $p1->setNode($n1);
        $p1->setModule('myModule');
        $p1->setCmd('cmd1');

        $p2 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p2->setLang(2);
        $p2->setTitle('rootTitle_1');
        $p2->setNode($n1);
        $p2->setModule('myModule');
        $p2->setCmd('cmd1');


        $p3 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p3->setLang(3);
        $p3->setTitle('rootTitle_2');
        $p3->setNode($n1);
        $p3->setModule('myModule');
        $p3->setCmd('cmd2');


        self::$em->persist($n1);

        self::$em->persist($p1);
        self::$em->persist($p2);
        self::$em->persist($p3);

        self::$em->flush();

        //make sure we re-fetch a correct state
        self::$em->clear();

        //test correct fetching
        $pages = $repo->getFromModuleCmdByLang('myModule');

        $this->assertArrayHasKey(1,$pages);
        $this->assertArrayHasKey(2,$pages);
        $this->assertArrayHasKey(3,$pages);

        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page', $pages[1]);
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page', $pages[2]);
        $this->assertInstanceOf('Cx\Core\ContentManager\Model\Entity\Page', $pages[3]);

        $this->assertEquals(1, $pages[1]->getLang());
        $this->assertEquals(2, $pages[2]->getLang());
        $this->assertEquals(3, $pages[3]->getLang());

        //test behaviour on specified cmd
        $pages = $repo->getFromModuleCmdByLang('myModule', 'cmd1');
        $this->assertEquals(2, count($pages));
    }

    public function testGetURL() {
        $root = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n1->setParent($root);
        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(1);
        $p1->setTitle('root');
        $p1->setNode($n1);
        self::$em->persist($root);
        self::$em->persist($n1);
        self::$em->persist($p1);
        self::$em->flush();
        //make sure we re-fetch a correct state
        self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Node')->verify();

        $pageRepo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');
        $url = $pageRepo->getURL($p1,'http://example.com', '?k=v');
        $this->assertEquals('http://example.com/root?k=v', $url);

        $url = $pageRepo->getURL($p1, '', '?k=v');
        $this->assertEquals('/root?k=v', $url);
    }

    public function testGetPathToPage() {
        $root = new \Cx\Core\ContentManager\Model\Entity\Node();

        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n1->setParent($root);
        $n2 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n2->setParent($n1);

        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(1);
        $p1->setTitle('root');
        $p1->setNode($n1);

        $p2 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p2->setLang(1);
        $p2->setTitle('child page');
        $p2->setNode($n2);

        self::$em->persist($root);

        self::$em->persist($n1);
        self::$em->persist($n2);

        self::$em->persist($p1);
        self::$em->persist($p2);

        $idOfP2 = $p2->getId();

        self::$em->flush();

        //make sure we re-fetch a correct state
        self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Node')->verify();

        $pageRepo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');

        $page = $pageRepo->findOneById($p2->getId());
      
        $this->assertEquals('root/child-page', $pageRepo->getPath($page));
    }
    
    public function testTranslate() {
        $root = new \Cx\Core\ContentManager\Model\Entity\Node();

        $n1 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n1->setParent($root);
        $n2 = new \Cx\Core\ContentManager\Model\Entity\Node();
        $n2->setParent($n1);

        $p1 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p1->setLang(1);
        $p1->setTitle('root');
        $p1->setNode($n1);

        $p2 = new \Cx\Core\ContentManager\Model\Entity\Page();     
        $p2->setLang(1);
        $p2->setTitle('child page');
        $p2->setNode($n2);

        self::$em->persist($root);

        self::$em->persist($n1);
        self::$em->persist($n2);

        self::$em->persist($p1);
        self::$em->persist($p2);

        $idOfP2 = $p2->getId();

        self::$em->flush();

        //make sure we re-fetch a correct state
        self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Node')->verify();

        $pageRepo = self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Page');

        $page = $pageRepo->findOneById($p2->getId());

        $t = $pageRepo->translate($page, 2);

        self::$em->flush();
        self::$em->getRepository('Cx\Core\ContentManager\Model\Entity\Node')->verify();

        $this->assertEquals('root/child-page', $pageRepo->getPath($t));
        $this->assertEquals(2, $t->getLang());

        //see if the parent node is really, really there.
        $parentPages = $t->getNode()->getParent()->getPagesByLang();
        $this->assertArrayHasKey(2, $parentPages);
        $this->assertEquals('root', $parentPages[2]->getTitle());
    }
}
