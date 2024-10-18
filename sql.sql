SELECT users.username, SUM(kind_points.points) as total_points
FROM kind_points
JOIN users ON kind_points.user_id = users.id
WHERE kind_points.date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)  -- 1ヶ月以内
GROUP BY users.username
ORDER BY total_points DESC;

