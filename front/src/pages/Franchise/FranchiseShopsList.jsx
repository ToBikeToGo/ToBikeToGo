import * as React from 'react';
import Typography from '@mui/material/Typography';

const mockShops = [
  {
    id: 1,
    name: 'BikeCity Rouen',
    address: '1 rue de la paix',
  },
  {
    id: 2,
    name: 'BikeCity Paris',
    address: '1 rue de la paix',
  },
  {
    id: 3,
    name: 'BikeCity Lyon',
    address: '1 rue de la paix',
  },
];

function FranchiseShopsList() {
  return (
    //         <List sx={{ width: '100%', maxWidth: '80%', bgcolor: 'background.paper', margin: '2em' }}>
    //
    //             {mockShops.map((shop) => (
    //                 <>
    //                 <ListItem alignItems="flex-start">
    //                     <ListItemAvatar>
    //                         <Avatar alt="Remy Sharp" src="/static/images/avatar/1.jpg" />
    //                     </ListItemAvatar>
    //                     <ListItemText
    //                         primary={shop.name}
    //                         secondary={
    //                             <React.Fragment>
    //                                 <Typography
    //                                     sx={{ display: 'inline' }}
    //                                     component="span"
    //                                     variant="body2"
    //                                     color="text.primary"
    //                                 >
    //                                     {shop.address}
    //                                 </Typography>
    //                                 {" — I'll be in your neighborhood doing errands this…"}
    //                             </React.Fragment>
    //                         }
    //                     />
    //                 </ListItem>
    //                 <Divider variant="inset" component="li" />
    // </>
    //             ))}
    //         </List>

    <div className={'flex w-3/4 justify-center'}>
      {mockShops.map((shop) => (
        <div className={'flex flex-col w-1/3 m-4'}>
          <div
            className={
              'flex flex-col items-center bg-white justify-center rounded-xl'
            }
          >
            <img
              src={'https://picsum.photos/200/300'}
              alt={'shop'}
              className={'w-full h-1/2'}
            />

            <Typography
              sx={{ display: 'inline' }}
              component="span"
              variant="body2"
              color="text.primary"
            >
              {shop.address}
            </Typography>

            <Typography
              sx={{ display: 'inline' }}
              component="span"
              variant="body2"
              color="text.primary"
            >
              {shop.name}
            </Typography>
          </div>
        </div>
      ))}
    </div>
  );
}

export { FranchiseShopsList };
