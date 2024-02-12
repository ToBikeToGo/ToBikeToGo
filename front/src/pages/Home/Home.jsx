import Typography from '@mui/material/Typography';
import { Parallax } from 'react-parallax';
import BikeImg from './../../assets/images/bike-home.jpg';
import PhoneMockup1 from './../../assets/images/phoneMockup.png';
import Button from '@mui/material/Button';
import { useTranslation } from '../../locales/hooks/getTranslation';

const Home = () => {
  const { getTranslation } = useTranslation();
  return (
    <>
      <div className={'flex justify-center items-center w-full flex-col'}>
        <Parallax bgImage={BikeImg} strength={800}>
          <div style={{ height: 800, width: '100vw' }}>
            <Typography
              variant="h2"
              component="h4"
              gutterBottom
              color={'black'}
              sx={{
                marginTop: '10em',
              }}
            >
              {getTranslation('home.title')}
            </Typography>
          </div>
        </Parallax>
        <div className="flex w-full p-20">
          <img
            src={PhoneMockup1}
            alt=""
            style={{
              width: '50%',
              objectFit: 'contain',
            }}
          />
          <div className="flex flex-col items-center w-full justify-center">
            <Typography
              variant="h2"
              component="h4"
              gutterBottom
              color={'black'}
            >
              {getTranslation('home.rent')}
            </Typography>
            <Typography
              variant="h5"
              noWrap
              href="#app-bar-with-responsive-menu"
            >
              {getTranslation('home.rent-subtitle')}
            </Typography>
            <Button
              variant="contained"
              color="black"
              component={'a'}
              size="large"
              sx={{
                mt: 4,
                ':hover': {
                  backgroundColor: 'black',
                  color: 'white',
                },
              }}
              href={'/rent/bike'}
            >
              {getTranslation('home.rent')}
            </Button>
          </div>
        </div>
      </div>
    </>
  );
};

export { Home };
