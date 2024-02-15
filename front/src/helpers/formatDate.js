export const toShortDate = (date) => {
  const newDate = new Date(date);
  return newDate.toLocaleDateString('en-US', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
};

const formatScheduleToText = (schedule) => {
  let text = '';
  for (const day in schedule) {
    text += `${day} : ${schedule[day].join(', ')}\n`;
  }
  return text;
};

export function formatSchedule(scheduleData) {
  console.log('scheduleData', scheduleData);
  if (!Array.isArray(scheduleData)) {
    return "Pas d'horaires renseignés.";
  }
  const daysOfWeek = [
    'Dimanche',
    'Lundi',
    'Mardi',
    'Mercredi',
    'Jeudi',
    'Vendredi',
    'Samedi',
  ];

  // Objet pour stocker les horaires formatés
  const formattedSchedule = {};

  scheduleData.forEach((entry) => {
    const day = daysOfWeek[entry.dow];
    const startTime = entry.startTime.split('T')[1].substring(0, 5);
    const endTime = entry.endTime.split('T')[1].substring(0, 5);

    if (!formattedSchedule[day]) {
      formattedSchedule[day] = [];
    }

    formattedSchedule[day].push(`${startTime} - ${endTime}`);
  });

  return formatScheduleToText(formattedSchedule);
}
