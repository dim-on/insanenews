<?php

class Page extends Model
{

    public function getCategoryList() {// todo разобраться с only published
        $sql = "SELECT category_id, category_alias, category_name, is_published FROM news_category";
        $result = $this->db->query($sql);
        $data ['category_name'] = array_column($result,'category_name');
        $data ['category_alias'] = array_column($result,'category_alias');
        $data ['category_id'] = array_column($result,'category_id');
        $data ['is_published'] = array_column($result,'is_published');
        return $data;
    }

    public function getPublishedCategoryList() {// todo разобраться с only published
        $sql = "SELECT category_id, category_alias, category_name, is_published FROM news_category WHERE is_published=1";
        $result = $this->db->query($sql);
        $data ['category_name'] = array_column($result,'category_name');
        $data ['category_alias'] = array_column($result,'category_alias');
        $data ['category_id'] = array_column($result,'category_id');
        $data ['is_published'] = array_column($result,'is_published');
        return $data;
    }

    public function getCategoryById($id){
        $id = (int) $id;
        $sql = "SELECT * FROM news_category WHERE category_id = '{$id}' LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function saveCategory($data, $id = null){
        if ( !isset($data['category_name']) || !isset($data['category_alias'])) {
            return false;
        }

        $id = (int)$id;
        $category_name = $this->db->escape($data['category_name']);
        $category_alias = $this->db->escape($data['category_alias']);
        $is_published = isset($data['is_published']) ? 1 : 0;

        if (!$id){//Add new record
            $sql = "
                INSERT INTO news_category
                SET category_name = '{$category_name}',
                    category_alias = '{$category_alias}',
                    is_published = '{$is_published}'
            ";

        } else {//Update existing record
            $sql = "
                UPDATE news_category
                SET category_name = '{$category_name}',
                    category_alias = '{$category_alias}',
                    is_published = {$is_published}
                WHERE category_id = '{$id}'
            ";
        }

        return $this->db->query($sql);

    }

    public function deleteCategory ($id) {
        $id = (int)$id;
        $sql = "
            DELETE FROM news_category
            WHERE category_id = '{$id}'
        ";
        return $this->db->query($sql);
    }

    public function getNewsList() {// todo разобраться с only published
        $sql = "SELECT n.news_id, n.news_title, n.picture, n.news_text, n.is_published, n.parent_category, c.category_name 
                FROM news n
                JOIN news_category c ON (n.parent_category = c.category_id) ORDER BY date DESC ";
        $result = $this->db->query($sql);
        $data ['news_id'] = array_column($result,'news_id');
        $data ['news_title'] = array_column($result,'news_title');
        $data ['picture'] = array_column($result,'picture');
        $data ['news_text'] = array_column($result,'news_text');
        $data ['is_published'] = array_column($result,'is_published');
        $data ['category_name'] = array_column($result,'category_name');
        $data ['parent_category'] = array_column($result,'parent_category');
        return $data;
    }

    public function getNewsListByAlias($alias){
        $alias = $this->db->escape($alias);
        $sql = "SELECT n.news_id, n.news_title, n.news_text, n.picture, n.date, nc.category_name, nc.category_alias
                FROM news n
                JOIN news_category nc ON (n.parent_category = nc.category_id)
                WHERE parent_category = 
                (SELECT category_id 
                FROM news_category 
                WHERE category_alias = '{$alias}')";
        $result = $this->db->query($sql);

        return isset($result) ? $result : null;
    }

    public function getNewsByNewsIdCategoryAlias($id, $category_alias) {
        $id = (int)$id;
        $sql = "SELECT * FROM news WHERE news_id = '{$id}' && 
                (SELECT category_id FROM news_category 
                WHERE category_alias = '{$category_alias}' LIMIT 1) = parent_category LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result) ? $result : null;
    }

    public function getNewsByIdParentCategory($id, $parent_category) {
        $id = (int)$id;
        $sql = "SELECT * FROM news WHERE news_id = '{$id}' && parent_category = '{$parent_category}' LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function get5LatestNewsByCat($arr_cat) {
        foreach ($arr_cat ['category_id'] as $category_id ) {
            $sql = "SELECT n.news_id, n.news_title, n.parent_category, c.category_alias, c.category_name 
                    FROM news n 
                    JOIN news_category c ON (n.parent_category = c.category_id)
                    WHERE parent_category = '{$category_id}'
                    ORDER BY date DESC LIMIT 5";
            $result [$category_id] = $this->db->query($sql);
        }
        return isset($result) ? $result : null;
    }

    public function get4LatestNews() {
            $sql = "SELECT n.news_id, n.news_title, c.category_alias, n.picture 
                    FROM news n 
                    JOIN news_category c ON (n.parent_category = c.category_id)
                    ORDER BY date DESC LIMIT 4";
            $result = $this->db->query($sql);

        return isset($result) ? $result : null;
    }

    public function saveNews($data, $id = null) {
        if ( !isset($data['news_title']) || !isset($data['news_text'])) {
            return false;
        }

        $id = (int)$id;
        $news_title = $this->db->escape($data['news_title']);
        $picture = $this->db->escape($data['picture']);
        $news_text = $this->db->escape($data['news_text']);
        $parent_category = $this->db->escape($data['parent_category']);
        $is_published = isset($data['is_published']) ? 1 : 0;
        $is_analytics = isset($data['is_analytics']) ? 1 : 0;

        if (!$id){//Add new record
            $sql = "
                INSERT INTO news
                SET news_title = '{$news_title}',
                    picture = '{$picture}',
                    news_text = '{$news_text}',
                    parent_category = '{$parent_category}',
                    is_published = '{$is_published}',
                    is_analytics = '{$is_analytics}'
            ";

        } else {//Update existing record
            $sql = "
                UPDATE news
                SET news_title = '{$news_title}',
                    picture = '{$picture}',
                    news_text = '{$news_text}',
                    parent_category = '{$parent_category}',
                    is_published = '{$is_published}',
                    is_analytics = '{$is_analytics}'
                WHERE news_id = '{$id}'
            ";
        }

        return $this->db->query($sql);

    }

    public function deleteNews ($id) {
        $id = (int)$id;
        $sql = "
            DELETE FROM news
            WHERE news_id = '{$id}'
        ";
        return $this->db->query($sql);
    }

    public function getCommentsByNewsId($id) {
        $id = (int)$id;
        $sql = "SELECT c.comment_id, c.message, c.date, c.count, u.avatar, u.user_name, u.user_id FROM comments c JOIN users u USING(user_id) WHERE c.parent_news = '{$id}' ORDER BY c.count DESC";
        $result = $this->db->query($sql);
        return isset($result) ? $result : null;
    }

    public function getCommentsByUserId ($id) {
        $id = (int)$id;
        $sql = "SELECT c.message, c.date, c.count, u.avatar, u.user_name, u.user_id FROM comments c JOIN users u USING(user_id) WHERE c.user_id = '{$id}' ORDER BY c.count DESC";
        $result = $this->db->query($sql);
        return isset($result) ? $result : null;
    }

    public function getTop5CommentatorsList() {
        $sql = "SELECT u.user_id, u.user_name, count(c.message) AS quant_messages 
                FROM users u 
                JOIN comments c USING (user_id) 
                GROUP by c.user_id
                ORDER by quant_messages DESC 
                LIMIT 5";//todo не работает limit
        $result = $this->db->query($sql);
        return isset($result) ? array_slice($result, 0, 5)  : null;
    }

    public function getTagsByNewsId ($id) {
        $id = (int)$id;
        $sql = "SELECT t.tag_name 
                FROM news_tags nt
                JOIN tags t ON nt.tag_id = t.tag_id
                JOIN news n ON nt.news_id = n.news_id
                WHERE nt.news_id='{$id}'";
        $result = $this->db->query($sql);
        return isset($result) ? $result : null;
    }

    public function getTagsList () {
        $sql = "SELECT tag_name 
                FROM tags";
        $result = $this->db->query($sql);
        $result = array_column($result, 'tag_name');
        return isset($result) ? $result : null;
    }


}