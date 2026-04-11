async function getPassages(testId) {
    try {
        if (!testId) {
            throw new Error('test_id is required');
        }

        const response = await fetch(`${API_BASE_URL}?path=/api/passages&test_id=${testId}`, {
            method: 'GET'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message || 'Failed to fetch passages');
        }

        return result.data || [];
    } catch (error) {
        console.error('Error fetching passages:', error);
        throw error;
    }
}