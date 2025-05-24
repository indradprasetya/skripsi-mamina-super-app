export function getAgeInMonths(birthdateStr) {
    const birthdate = new Date(birthdateStr);
    const now = new Date();
    let months = (now.getFullYear() - birthdate.getFullYear()) * 12 + (now.getMonth() - birthdate.getMonth());
    if (now.getDate() < birthdate.getDate()) {
        months--;
    }
    const years = Math.floor(months / 12);
    const remainingMonths = months % 12;
    return {
        years,
        months: remainingMonths,
        totalMonths: months
    };
}

export function getDateIndonesia(date) {
    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}