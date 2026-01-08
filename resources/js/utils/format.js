export function formatStatus(status) {
    return status
        .replace(/_/g, " ")
        .replace(/\b\w/g, c => c.toUpperCase());
}
