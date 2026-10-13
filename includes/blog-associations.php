<?php

function loadBlogAssociations(PDO $db, array $blogIds): array
{
    $blogIds = array_values(array_unique(array_filter(
        $blogIds,
        static fn($id): bool => is_string($id) && isAdminId($id)
    )));
    if ($blogIds === []) {
        return [];
    }

    $parameters = [];
    $placeholders = [];
    foreach ($blogIds as $index => $blogId) {
        $parameter = 'blog_id_' . $index;
        $placeholders[] = ':' . $parameter;
        $parameters[$parameter] = $blogId;
    }
    $inClause = implode(', ', $placeholders);
    $associations = [];

    $statement = $db->prepare(
        'SELECT cm.blog_id, c.id, c.name, c.slug
         FROM category_map cm
         INNER JOIN blog_categories c ON c.id = cm.category_id
         WHERE cm.blog_id IN (' . $inClause . ')
         ORDER BY c.name ASC'
    );
    $statement->execute($parameters);
    foreach ($statement->fetchAll() as $category) {
        $associations[$category['blog_id']]['categories'][] = [
            'id' => $category['id'],
            'name' => $category['name'],
            'slug' => $category['slug'],
        ];
    }

    $statement = $db->prepare(
        'SELECT tm.blog_id, t.id, t.name, t.slug
         FROM tag_map tm
         INNER JOIN blog_tags t ON t.id = tm.tag_id
         WHERE tm.blog_id IN (' . $inClause . ')
         ORDER BY t.name ASC'
    );
    $statement->execute($parameters);
    foreach ($statement->fetchAll() as $tag) {
        $associations[$tag['blog_id']]['tags'][] = [
            'id' => $tag['id'],
            'name' => $tag['name'],
            'slug' => $tag['slug'],
        ];
    }

    foreach ($blogIds as $blogId) {
        $associations[$blogId] ??= [];
        $associations[$blogId]['categories'] ??= [];
        $associations[$blogId]['tags'] ??= [];
    }

    return $associations;
}

function saveBlogAssociations(PDO $db, string $blogId, array $categoryIds, array $tagIds): void
{
    $statement = $db->prepare('DELETE FROM category_map WHERE blog_id = :blog_id');
    $statement->execute(['blog_id' => $blogId]);
    $statement = $db->prepare('DELETE FROM tag_map WHERE blog_id = :blog_id');
    $statement->execute(['blog_id' => $blogId]);

    $categoryInsert = $db->prepare(
        'INSERT INTO category_map (id, blog_id, category_id) VALUES (:id, :blog_id, :category_id)'
    );
    foreach ($categoryIds as $categoryId) {
        $categoryInsert->execute([
            'id' => newAdminId(),
            'blog_id' => $blogId,
            'category_id' => $categoryId,
        ]);
    }

    $tagInsert = $db->prepare('INSERT INTO tag_map (id, blog_id, tag_id) VALUES (:id, :blog_id, :tag_id)');
    foreach ($tagIds as $tagId) {
        $tagInsert->execute([
            'id' => newAdminId(),
            'blog_id' => $blogId,
            'tag_id' => $tagId,
        ]);
    }
}
