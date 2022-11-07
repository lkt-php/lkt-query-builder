<?php

namespace Lkt\Templates\Tests;

use Lkt\QueryBuilding\Query;
use Lkt\QueryBuilding\Where;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /**
     * @return void
     */
    public function testSimpleQuery()
    {
        $builder = Query::table('users')
            ->where(Where::stringEqual('name', 'John')->andStringEqual('surname', 'Doe'))
            ->setColumns(['*'])
            ;

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND ((name='John' AND surname='Doe'))", trim($builder->getSelectQuery()));

        $builder = Query::table('users')
            ->setColumns(['*'])
            ->andStringEqual('name', 'John')
            ->andStringEqual('surname', 'Doe');

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND (name='John' AND surname='Doe')", trim($builder->getSelectQuery()));

        $builder = Query::table('users')
            ->setColumns(['*'])
            ->andStringEqual('name', 'John')
            ->andWhere(Where::stringEqual('surname', 'Doe')->orStringEqual('surname', 'Smith'))
            ;

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND (name='John' AND (surname='Doe' OR surname='Smith'))", trim($builder->getSelectQuery()));
    }

    /**
     * @return void
     */
    public function testSimpleQuery2()
    {
        $builder = Query::table('users')
            ->where(Where::stringEqual('name', 'John')->andWhere(Where::stringEqual('surname', 'Doe')))
            ->setColumns(['*'])
            ;

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND ((name='John' AND (surname='Doe')))", trim($builder->getSelectQuery()));
    }

    /**
     * @return void
     */
    public function testSimpleQueryWithOr()
    {
        $builder = Query::table('users')
            ->where(Where::stringEqual('name', 'John')->orWhere(Where::stringEqual('surname', 'Doe')))
            ->setColumns(['*'])
            ;

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND ((name='John' OR (surname='Doe')))", trim($builder->getSelectQuery()));
    }

    /**
     * @return void
     */
    public function testSimpleQueryWithOr2()
    {
        $builder = Query::table('users')
            ->where(Where::stringEqual('name', 'John')->orStringEqual('surname', 'Doe'))
            ->setColumns(['*'])
            ;

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND ((name='John' OR surname='Doe'))", trim($builder->getSelectQuery()));
    }

    /**
     * @return void
     */
    public function testMultipleConstraints()
    {
        $builder = Query::table('users')
            ->where(Where::stringEqual('name', 'John')->orWhere(Where::stringEqual('surname', 'Doe')))
            ->where(Where::integerNot('id', 0))
            ->setColumns(['*'])
            ;

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND ((name='John' OR (surname='Doe')) AND (id!=0))", trim($builder->getSelectQuery()));
    }

    /**
     * @return void
     */
    public function testSubquery()
    {
        $builder = Query::table('users')
            ->where(Where::stringEqual('name', 'John')->orWhere(Where::stringEqual('surname', 'Doe')))
            ->where(Where::integerNot('id', 0))
            ->where(Where::subQueryEqual(Query::table('users')->setColumns(['id'])->where(Where::integerEqual('id', 1)), 1))
            ->setColumns(['*'])
            ;

        $this->assertEquals("SELECT  users.* FROM users  WHERE 1  AND ((name='John' OR (surname='Doe')) AND (id!=0) AND ((SELECT  users.id FROM users  WHERE 1  AND ((id=1))  )=1))", trim($builder->getSelectQuery()));
    }
}