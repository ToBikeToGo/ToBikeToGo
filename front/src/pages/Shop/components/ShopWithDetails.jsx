export const ShopWithDetails = ({ shop, canEdit = false }) => {
  return (
    <div className={'flex flex-col md:flex-row'}>
      <div className={'md:w-1/2'}>
        <img
          src={
            'https://ezeryders.com/cdn/shop/products/ScreenShot2022-02-23at5.37.41PM.png?v=1645666853'
          }
          alt={shop.name}
        />
      </div>
      <div className={'md:w-1/2'}>
        <div className={'flex flex-col'}>
          <p className={'text-2xl font-bold'}>{shop.label}</p>
          <p className={'text-2xl'}>{shop.address}</p>
          <p className={'font-semibold'}>{shop.phone}</p>
          <p className={'font-semibold'}>{shop.email}</p>
          <p className={'font-semibold'}>{shop.website}</p>
          <p className={'font-semibold'}>{shop.description}</p>
        </div>
      </div>
    </div>
  );
};
