import Typography from '@mui/material/Typography';
import { Parallax } from 'react-parallax';
import BikeImg from './../../assets/images/bike-home.jpg';
import PhoneMockup1 from './../../assets/images/phoneMockup.png';
import Button from '@mui/material/Button';

const Home = () => {
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
              Let's ride !
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
              Rent a bike
            </Typography>
            <Typography
              variant="h5"
              noWrap
              href="#app-bar-with-responsive-menu"
            >
              Everything becomes easier with BikeToGo
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
              Rent a bike
            </Button>
          </div>
        </div>
      </div>
    </>
  );
};

export { Home };
