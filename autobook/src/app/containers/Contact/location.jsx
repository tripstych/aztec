import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import { APIProvider, Map, AdvancedMarker } from "@vis.gl/react-google-maps";

const SectionContainer = styled.div`
  ${tw`
    flex
    items-center
    justify-center
    w-full
    bg-gray-100
  `}
`;

const ContentWrapper = styled.div`
  ${tw`
    flex
    flex-col
    lg:flex-row
    items-start
    justify-between
    w-full
    px-6
  `}
`;

const MapContainer = styled.div`
  ${tw`
    w-full
    h-[450px]
    rounded-lg
    overflow-hidden
  `}
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
`;

const Location = () => {
  const mapStyles = {
    height: "100%",
    width: "100%",
  };

  const location = {
    lat: 51.262190267731846, // Latitude of the location
    lng: -113.99412257024366, // Longitude of the location
  };

  return (
    <SectionContainer>
      <ContentWrapper>
        <MapContainer>
          <APIProvider apiKey={process.env.REACT_APP_GOOGLE_MAPS_API_KEY}>
            <Map
              mapId={process.env.REACT_APP_MAP_ID}
              style={mapStyles}
              defaultZoom={19}
              defaultCenter={location}
            >
              <AdvancedMarker position={location} />
            </Map>
          </APIProvider>
        </MapContainer>
      </ContentWrapper>
    </SectionContainer>
  );
};

export default Location;
