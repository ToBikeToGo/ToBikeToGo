import { getMapCoordonate } from '../helpers/getMapCoordonate';

describe('getMapCoordonate', () => {
  it('returns coordinates for a valid address', async () => {
    const address = '5 rue de la paix';
    const coordinates = await getMapCoordonate(address);
    expect(coordinates).toEqual({ lat: '48.0711356', lon: '-0.7695016' });
  });
});
