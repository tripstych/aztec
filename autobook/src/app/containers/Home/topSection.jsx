import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import Button from "../../components/button";
import LandingVideo from "../../components/assets/images/landing_2.mov";

const TopSectionContainer = styled.div`
  ${tw`
    w-full
    flex
    justify-between
  `}
`;

const HeaderSection = styled.div`
  ${tw`
    relative
    flex
    flex-col
    w-full
    items-center
    justify-center
    min-h-screen
    px-6
  `}

  &::before {
    content: "";
    ${tw`
      absolute
      top-0
      left-0
      w-full
      h-full
    `}
    background-color: rgba(0, 0, 0, 0.75); /* Overlay for text readability */
    z-index: 2;
  }

  > * {
    z-index: 2; /* Ensures text and buttons are above the overlay */
    ${tw`
      text-center
    `}
  }
`;

const VideoBackground = styled.video`
  ${tw`
    absolute
    top-0
    left-0
    w-full
    h-full
  `}
  object-fit: cover; /* Ensures the video covers the container without distortion */
  z-index: 1; /* Places the video behind all other elements */
`;

const Slogan = styled.h1`
  ${tw`
    font-black
    text-3xl
    xl:text-8xl
    md:text-5xl
    text-white
    sm:leading-tight
    lg:leading-snug
    xl:leading-normal
  `}
`;

const SubSlogan = styled.h2`
  ${tw`
    text-2xl 
    md:text-7xl 
    tracking-wide 
    font-extrabold 
    text-cBlue
    mb-8
  `}
`;

const Description = styled.p`
  ${tw`
    text-sm
    xl:text-lg
    text-gray-200
    font-normal
    md:mt-4
    max-w-xl
  `}
`;

const ButtonsContainer = styled.div`
  ${tw`
    flex
    items-center
    justify-center
    md:space-x-6
    gap-2
    md:gap-0
    mt-8
    flex-wrap
  `}
`;

const TopSection = () => {
  return (
    <TopSectionContainer>
      <HeaderSection>
        {/* Video Background */}
        <VideoBackground src={LandingVideo} autoPlay loop muted playsInline />
        {/* Content */}
        <Slogan>Same-Day Autoglass </Slogan>
        <SubSlogan>Replacements</SubSlogan>
        <Description>
          Get in touch today for a free, no-obligation quote and let us handle
          your auto glass needs with ease and professionalism.
        </Description>
        <ButtonsContainer>
          <Button theme="filled" text="Get Free Quote" />
          <Button theme="outlined" text="Call Us Now" phone="5879667636" />
        </ButtonsContainer>
      </HeaderSection>
    </TopSectionContainer>
  );
};

export default TopSection;
