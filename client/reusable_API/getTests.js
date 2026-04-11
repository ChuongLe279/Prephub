async function getTests() {
    try {
        const response = await fetch(`${API_BASE_URL}?path=/api/tests`, {
            method: 'GET'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message || 'Failed to fetch tests');
        }

        return result.data || [];
    } catch (error) {
        console.error('Error fetching tests:', error);
        throw error;
    }
}