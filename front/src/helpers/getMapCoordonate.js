const getMapCoordonate = (address) => {
  const stringifyAdress = address.replace(/ /g, '+');
  return fetch(
    'https://nominatim.openstreetmap.org/search?q=' +
      stringifyAdress +
      '&format=json&limit=1'
  )
    .then((response) => response.json())
    .then((data) => {
      return {
        lat: data[0]?.lat,
        lon: data[0]?.lon,
      };
    })
    .catch((error) => {
      console.log(error);
    });
};

export { getMapCoordonate };
