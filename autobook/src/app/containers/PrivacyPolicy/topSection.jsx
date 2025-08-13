import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import GlassImg from "../../components/assets/images/bg_2.jpg";

const TopSectionContainer = styled.div`
  /* min-height: 400px; */
  ${tw`
    w-full
    // max-w-screen-2xl
    flex
    justify-between
  `}
`;

const HeaderSection = styled.div`
  ${tw`
    w-full
    h-[300px]
    md:h-[400px] 
    flex
    items-center
    justify-center
    text-white
  `}
  background: url(${GlassImg}) center/cover no-repeat;
  background-attachment: fixed;
  position: relative;

  &::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.65); /* Adjust the opacity (0.5) as needed */
    z-index: 1;
  }

  > * {
    position: relative;
    z-index: 2;
  }
`;

const HeaderText = styled.h1`
  ${tw`
    text-4xl
    md:text-6xl
    font-bold
  `}
`;

const TopSection = () => {
  return (
    <TopSectionContainer>
      <HeaderSection>
        <HeaderText>
          Privacy <span className="text-cBlue">Policy</span>
        </HeaderText>
      </HeaderSection>
    </TopSectionContainer>
  );
};

export default TopSection;
